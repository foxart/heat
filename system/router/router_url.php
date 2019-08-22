<?php

$authorizationData = $faAuthorize->get_login();

if (isset($_GET['host']) == true) {
	$get_host = $_GET['host'];
	$sql_select = "
			SELECT
				ush.fk_host AS host_id
			FROM
				user_statistic_host AS ush
			WHERE
				ush.fk_user = |USER|
			AND
				ush.fk_host = |HOST|
			ORDER BY
				ush.id ASC
		";
	$faSql->load($sql_select);
	$faSql->set(array(
		'USER' => $authorizationData['user_id'],
		'HOST' => $get_host,
	));
	// view($faSql->query);
	$faSql->run();
	$records = $faSql->num_rows;
	if ($records == 1) {
		$faAuthorize->set_login_parameter('host_id', $get_host);
	};

	if ($authorizationData['user_id'] == 0) {
		$faAuthorize->set_login_parameter('host_id', $get_host);
	};
};

if (isset($_GET['link']) == true) {
	$get_link = $_GET['link'];
} else {
	$get_link = 0;
};

if (isset($_GET['user']) == true) {
	$get_user = $_GET['user'];
} else {
	$get_user = 0;
};

if (isset($_GET['date_start']) == true) {
	$get_date_start = $_GET['date_start'];
	$get['date_start'] = $get_date_start;
} else {
	// $get_date_start = date('Y-m-d');
	$get_date_start = '';
};

if (isset($_GET['date_end']) == true) {
	$get_date_end = $_GET['date_end'];
	$get['date_end'] = $get_date_end;
} else {
	// $get_date_end = date('Y-m-d');
	$get_date_end = '';
};


if (isset($_GET['year']) == true) {
	$get_year = $_GET['year'];
} else {
	$get_year = 0;
};

if (isset($_GET['month']) == true) {
	$get_month = $_GET['month'];
} else {
	$get_month = 0;
};

if (isset($_GET['day']) == true) {
	$get_day = $_GET['day'];
} else {
	$get_day = 0;
};
