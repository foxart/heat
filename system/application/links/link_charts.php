<?php

$sql_select = "
		SELECT
			CAST(s.time AS DATE) AS date,
			DATE_FORMAT(s.time, '%Y') AS year,
			DATE_FORMAT(s.time, '%c') AS month,
			DATE_FORMAT(s.time, '%e') AS day,
			COUNT(s.fk_statistic_geo) AS users,
			SUM(s.enter) AS enters,
			SUM(s.exit) AS exits,
			SUM(s.click) AS clicks,
			SUM(s.move) AS moves,
			SUM(s.scroll) AS scrolls,
			SUM(s.move_time) AS move_time,
			SUM(s.scroll_time) AS scroll_time,
			SUM(s.spend_time) AS spend_time
		FROM
			statistic AS s
		WHERE
			IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
		GROUP BY
			year, month, day
		ORDER BY
			s.id |ORDER|

	";
$faSql->load($sql_select);
$faSql->set(array(
	'LINK_ID' => $get_link,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
	'ORDER' => 'ASC',
));
$faSql->run();
$records = $faSql->to_assoc();
// view($faSql);

$chartData['users'] = array();
$chartData['enters'] = array();
$chartData['exits'] = array();
$chartData['clicks'] = array();
$chartData['moves'] = array();
$chartData['scrolls'] = array();

if ($faSql->num_rows > 0) {
	$count = 0;
	$chartData = array();
	foreach ($records as $key => $value) {
		$count++;

		$date = $value['date'];

		$year = $value['year'];
		$month = $value['month'];
		$day = $value['day'];

		$users = (int) $value['users'];
		$enters = (int) $value['enters'];
		$exits = (int) $value['exits'];
		$clicks = (int) $value['clicks'];
		$moves = (int) $value['moves'];
		$scrolls = (int) $value['scrolls'];

		$chartData['users'][] = "{x: new Date('{$date}'), y: {$users}}";

		$chartData['enters'][] = "{x: new Date('{$date}'), y: {$enters}}";
		$chartData['exits'][] = "{x: new Date('{$date}'), y: {$exits}}";

		$chartData['clicks'][] = "{x: new Date('{$date}'), y: {$clicks}}";
		$chartData['moves'][] = "{x: new Date('{$date}'), y: {$moves}}";
		$chartData['scrolls'][] = "{x: new Date('{$date}'), y: {$scrolls}}";


		// $tableRows[$key]['move time'] = format_milliseconds($value['move_time']);
		// $tableRows[$key]['scroll time'] = format_milliseconds($value['scroll_time']);
		// $tableRows[$key]['spend time'] = format_milliseconds($value['spend_time']);
	};
} else {

};

$users = implode(',' . PHP_EOL, $chartData['users']);
$enters = implode(',' . PHP_EOL, $chartData['enters']);
$exits = implode(',' . PHP_EOL, $chartData['exits']);
$clicks = implode(',' . PHP_EOL, $chartData['clicks']);
$moves = implode(',' . PHP_EOL, $chartData['moves']);
$scrolls = implode(',' . PHP_EOL, $chartData['scrolls']);


$template = basename(__FILE__, EXT_PHP);
$ips_info_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$ips_info_result = $faTemplate->set($ips_info_template, array(
	'|URL_BACK|' => APPLICATION,
	// '|ITEMS|' => $items,
	'|USERS|' => $users,
	'|ENTERS|' => $enters,
	'|EXITS|' => $exits,
	'|CLICKS|' => $clicks,
	'|MOVES|' => $moves,
	'|SCROLLS|' => $scrolls,
		));

return $ips_info_result;
