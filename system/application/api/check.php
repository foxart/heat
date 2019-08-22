<?php

if (count($_POST) == 0) {
	echo 'no data';
	exit;
};

$result = array();
$result['status'] = 'success';
$result['statistic_url_image'] = 0;

$time = time();
$timeFormatted = date('Y-m-d', $time);

$post_ip = $_POST['ip'];
$post_url = $_POST['url'];
$post_time = $_POST['time'];
$post_width = $_POST['width'];
$post_height = $_POST['height'];

/* CHECK GEO */
$sql_check_geo = "
	SELECT sg.id AS geo_id
	FROM statistic_geo AS sg
	WHERE sg.ip = '|IP|'
";
$faSql->load($sql_check_geo);
$faSql->set(array(
	'IP' => $post_ip,
));
$faSql->run();

if ($faSql->num_rows == false) {
	$sql_select_geo = "
		SELECT
			glocation.id AS fk_geo_location, gcountry.id AS fk_geo_country, gcity.id AS fk_geo_city
		FROM
			geo_location AS glocation
		LEFT JOIN
			geo_city AS gcity ON gcity.id = glocation.fk_geo_city
		LEFT JOIN
			geo_country AS gcountry ON INET_ATON('|IP|') BETWEEN gcountry.ip_start AND gcountry.ip_end
		WHERE
			INET_ATON('|IP|') BETWEEN glocation.ip_start AND glocation.ip_end
	";
	$faSql->load($sql_select_geo);
	$faSql->set(array(
		'IP' => $post_ip
	));
	$faSql->run();
	$record_geo = $faSql->fetch_assoc();
	$fk_geo_location = $record_geo['fk_geo_location'];
	$fk_geo_country = $record_geo['fk_geo_country'];
	$fk_geo_city = $record_geo['fk_geo_city'];
	if (isset($fk_geo_location) == false) {
		$fk_geo_location = 0;
	};
	if (isset($fk_geo_country) == false) {
		$fk_geo_country = 0;
	};
	if (isset($fk_geo_city) == false) {
		$fk_geo_city = 0;
	};
	$sql_insert_geo = "
			INSERT INTO
				statistic_geo (fk_geo_location, fk_geo_country, fk_geo_city, ip)
			VALUES
				(|FK_GEO_LOCATION|, |FK_GEO_COUNTRY|, |FK_GEO_CITY|, '|IP|');
			SELECT LAST_INSERT_ID() AS geo_id
		";
	$faSql->load($sql_insert_geo);
	$faSql->set(array(
		'IP' => $post_ip,
		'FK_GEO_LOCATION' => $fk_geo_location,
		'FK_GEO_COUNTRY' => $fk_geo_country,
		'FK_GEO_CITY' => $fk_geo_city,
	));
	$faSql->run();
};
$record_geo = $faSql->fetch_assoc();
$geo_id = $record_geo['geo_id'];
if (isset($geo_id) == false) {
	$geo_id = 0;
};
$result['statistic_geo_id'] = $geo_id;
/* CHECK GEO */



/* CHECK URL */
$sql_check_url = "
		SELECT
			su.id AS url_id, su.width, su.height
		FROM
			statistic_url AS su
		WHERE
			su.url = '|URL|'
	";
$faSql->load($sql_check_url);
$faSql->set(array(
	'URL' => $post_url,
));
$faSql->run();


if ($faSql->num_rows == false) {
	$sql_insert_url = "
		INSERT INTO
			statistic_url (width, height, url)
		VALUES
			('|WIDTH|', '|HEIGHT|', '|URL|');
		SELECT LAST_INSERT_ID() AS url_id
	";
	$faSql->load($sql_insert_url);
	$faSql->set(array(
		'WIDTH' => $post_width,
		'HEIGHT' => $post_height,
		'URL' => $post_url,
	));
	$faSql->run();
	$record_url = $faSql->fetch_assoc();
	$url_id = $record_url['url_id'];
} else {
	$record_url = $faSql->fetch_assoc();
	$url_id = $record_url['url_id'];
	$url_width = $record_url['width'];
	$url_height = $record_url['height'];
}

if (isset($url_id) == false) {
	$url_id = 0;
}
$result['statistic_url_id'] = $url_id;
/* CHECK URL */



/* CHECK STATISTIC */
$sql_select = "
	SELECT
		s.id AS statistic_id
	FROM
		statistic AS s
	WHERE
		s.fk_statistic_geo = |GEO_ID|
	AND
		s.fk_statistic_url = |URL_ID|
	AND
		DATE_FORMAT(s.time, '%Y-%m-%d') = '|TIME|'
";
$faSql->load($sql_select);
$faSql->set(array(
	'TIME' => $timeFormatted,
	'GEO_ID' => $geo_id,
	'URL_ID' => $url_id,
));
$faSql->run();

// view($faSql);
// exit;
// $record_statistic = $faSql->fetch_assoc();
// $statistic_id = $record_statistic['statistic_id'];

if ($faSql->num_rows == 0) {
	$parse = parse_url($post_url);
	// view($parse);
	if (isset($parse['host']) == true) {
		$host = $parse['host'];
		$sql_select = "
			SELECT
				id AS host_id
			FROM
				statistic_host
			WHERE
				host = '|HOST|'
		";
		$faSql->load($sql_select);
		$faSql->set(array(
			'HOST' => $host,
		));
		$faSql->run();

		if ($faSql->num_rows == 0) {
			/* ADD NEW HOST TO TRACK */
			$sql_insert_url = "
				INSERT INTO
					statistic_host (host)
				VALUES
					('|HOST|');
				SELECT LAST_INSERT_ID() AS host_id
			";
			$faSql->load($sql_insert_url);
			$faSql->set(array(
				'HOST' => $host,
			));
			$faSql->run();
		}

		$record = $faSql->fetch_assoc();
		$host_id = $record['host_id'];
	} else {
		$host_id = 0;
	}



	/* ADD NEW STATISTIC RECORD */
	$sql_insert = "
		INSERT INTO
			statistic (fk_statistic_geo, fk_statistic_host, fk_statistic_url, `time`)
		VALUES
			(|FK_STATISTIC_GEO|, |FK_STATISTIC_HOST|, |FK_STATISTIC_URL|, FROM_UNIXTIME(|TIME|));
		SELECT LAST_INSERT_ID() AS statistic_id
	";
	$faSql->load($sql_insert);
	$faSql->set(array(
		'FK_STATISTIC_GEO' => $geo_id,
		'FK_STATISTIC_HOST' => $host_id,
		'FK_STATISTIC_URL' => $url_id,
		'TIME' => $time,
	));
	// echo $faSql->query;
	// exit;
	$faSql->run();
	/* ADD NEW STATISTIC RECORD */
} else if ($faSql->num_rows > 1) {
	$result['status'] = 'error';
};

$record_statistic = $faSql->fetch_assoc();
$statistic_id = $record_statistic['statistic_id'];
if (isset($statistic_id) == false) {
	$statistic_id = 0;
};
$result['statistic_id'] = $statistic_id;
/* CHECK STATISTIC */


/* TRACK VISIT */
$sql_insert = "
	UPDATE
		statistic AS s
	SET
		s.enter = IFNULL(s.enter, 0) + 1
	WHERE
		s.id = |FK_STATISTIC|;

	INSERT INTO
		statistic_action (fk_statistic, `time`, action, x, y, width, height)
	VALUES
		(|FK_STATISTIC|, |TIME|, '|ACTION|', |X|, |Y|, '|WIDTH|', '|HEIGHT|');
	SELECT LAST_INSERT_ID() AS statistic_id
";
$faSql->load($sql_insert);
$faSql->set(array(
	'FK_STATISTIC' => $statistic_id,
	'TIME' => $post_time,
	'ACTION' => 'enter',
	'X' => 0,
	'Y' => 0,
	'WIDTH' => $post_width,
	'HEIGHT' => $post_height,
));
$faSql->run();
/* TRACK VISIT */



$filenameImage = PATH_CACHE . $result['statistic_url_id'] . '.jpg';

if (file_exists($filenameImage) == true) {
	$image = new $faImage;
	$image->load($filenameImage);
	$imageWidth = $image->get_width();
	$imageHeight = $image->get_height();

	if ($post_width > $imageWidth or $post_height > $imageHeight) {
		$result['statistic_url_image'] = 1;
	} else {
		$result['statistic_url_image'] = 0;
	}
} else {
	$result['statistic_url_image'] = 1;
}


$output = json_encode($result);
// $output = $result;
return $output;
