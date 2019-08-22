<?php

$sql_select = "
		SELECT
			sg.ip,
			gcountry.code,
			gcountry.country,
			gcity.city,
			COUNT(s.id) AS 'links',
			COUNT(DISTINCT s.fk_statistic_url) AS 'links unique',
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
		LEFT JOIN
			statistic_url AS su ON su.id = s.fk_statistic_url
		LEFT JOIN
			statistic_geo AS sg ON sg.id = s.fk_statistic_geo
		LEFT JOIN
			geo_country AS gcountry ON gcountry.id = sg.fk_geo_country
		LEFT JOIN
			geo_city AS gcity on gcity.id = sg.fk_geo_city
		WHERE
			s.fk_statistic_geo = |IP_ID|
	";
$faSql->load($sql_select);
$faSql->set(array(
	'IP_ID' => $get_user,
));
$faSql->run();
// $count = $faSql->num_rows;
$records = $faSql->to_assoc();


$tableRows = array();
foreach ($records as $key => $value) {
	$flag = strtolower($value['code']);
	$tableRows[$key]['user'] = $value['ip'];
	$tableRows[$key]['country'] = $value['country'];
	$tableRows[$key]['flag'] = "<span class=\"flag flag_{$flag}\">&nbsp;</span>";
	$tableRows[$key]['city'] = $value['city'];
	$tableRows[$key]['links'] = $value['links'];
	$tableRows[$key]['links unique'] = $value['links unique'];
	$tableRows[$key]['enters'] = $value['enters'];
	$tableRows[$key]['exits'] = $value['exits'];
	$tableRows[$key]['clicks'] = $value['clicks'];
	$tableRows[$key]['moves'] = $value['moves'];
	$tableRows[$key]['scrolls'] = $value['scrolls'];
	$tableRows[$key]['move time'] = format_milliseconds($value['move_time']);
	$tableRows[$key]['scroll time'] = format_milliseconds($value['scroll_time']);
	$tableRows[$key]['spend time'] = format_milliseconds($value['spend_time']);

	// $url = US . DOMAIN . US . CONTROLLER . "?user={$value['id']}";
	// $records[$key]['ip'] = "<a href=\"{$url}\">{$value['ip']}</a>";
};

$user_info = fill_table($tableRows);

$template = basename(__FILE__, EXT_PHP);
$user_info_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_info_content = $faTemplate->set($user_info_template, array(
	'|ITEM|' => $user_info,
		));

return $user_info_content;
