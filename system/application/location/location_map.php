<?php

$sql = "
		SELECT
			COUNT(sg.id) AS count,
			gcountry.country, gcountry.code, gcity.city, gcity.latitude, gcity.longitude
		FROM
			statistic_geo AS sg
		LEFT JOIN
			geo_city AS gcity ON gcity.id = sg.fk_geo_city
		LEFT JOIN
			geo_country AS gcountry ON gcountry.id = sg.fk_geo_country
		LEFT JOIN
			statistic AS s ON s.fk_statistic_geo = sg.id
		WHERE
			IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
		AND
			IF('|COUNTRY|' = '0', 1, gcountry.code = '|COUNTRY|')
		GROUP BY
			IF('|COUNTRY|' = '0', gcountry.country, gcity.city)
	";
$faSql->load($sql);
$faSql->set(array(
	'HOST' => $authorizationData['host_id'],
	'COUNTRY' => $get_country,
));
$faSql->run();

//view($faSql->query);


$latitude = 0;
$longitude = 0;
$count = $faSql->num_rows;
if ($count > 0) {
	$geoData = $faSql->to_assoc();
	// view($geoData);
	$maxCount = 1;
	$minCount = 0;
	$countries = array();
	foreach ($geoData as $key => $value) {
		// if (isset($value['city']) == false)
		// {
		// if (isset($value['country']) == false)
		// {
		// $title = 'undefined';
		// } else {
		// $title = $value['country'];
		// };
		// } else {
		// $title = $value['city'];
		// };

		if ($get_country == '0') {
			$title = $value['country'];
		} else {
			$title = $value['city'];
		}


		if (isset($value['latitude']) == true and isset($value['longitude']) == true) {
			if ($maxCount < $value['count']) {
				$maxCount = $value['count'];
			};

			if ($minCount > $value['count']) {
				$minCount = $value['count'];
			};

			$countries[$key]['title'] = $title;
			$countries[$key]['count'] = $value['count'];
			$countries[$key]['latitude'] = $value['latitude'];
			$countries[$key]['longitude'] = $value['longitude'];

			$latitude = $latitude + $countries[$key]['latitude'] = $value['latitude'];
			$longitude = $longitude + $countries[$key]['longitude'] = $value['longitude'];
		}
	}


	$averageLatitude = $latitude / $count;
	$averageLongitude = $longitude / $count;

	$normalizeStart = 5;
	$normalizeEnd = 25;

	// view($countries);
	// exit;

	foreach ($countries as $key => $value) {
		$title = preg_replace("/[^A-Za-z0-9 ]/", '', $value['title']);

		$count = $value['count'];
		$countries[$key]['title'] = $title;
		$countries[$key]['size'] = normalize($count, $minCount, $maxCount, $normalizeStart, $normalizeEnd);
	}
	$location = json_encode($countries);
} else {
	$location = false;
}

$template = basename(__FILE__, EXT_PHP);
$location_map_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);

$location_map_content = $faTemplate->set($location_map_template, array(
	'|LATITUDE|' => $averageLatitude,
	'|LONGITUDE|' => $averageLongitude,
	'|LOCATION|' => $location,
		));

return $location_map_content;
