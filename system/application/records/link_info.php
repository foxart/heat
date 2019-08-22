<?php

$sql_select = "
		SELECT
			su.id,
			su.url,
			COUNT(sg.id) AS users,
			COUNT(DISTINCT sg.id) AS users_unique,
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
			su.id = |LINK_ID|
		GROUP BY
			su.id
	";


$faSql->load($sql_select);
$faSql->set(array(
	'LINK_ID' => $get_link,
));
$faSql->run();
// $count = $faSql->num_rows;
$records = $faSql->to_assoc();


$tableRows = array();
foreach ($records as $key => $value) {
	$tableRows[$key]['link'] = $value['url'];
	$tableRows[$key]['users'] = $value['users'];

	$users = $value['users'];
	if ($users < 1) {
		$users = 1;
	}

	$moves_time = $value['move_time'];
	$scrolls_time = $value['scroll_time'];
	$spend_time = $value['spend_time'];

	$tableRows[$key]['users unique'] = $value['users_unique'];

	$tableRows[$key]['enters'] = $value['enters'];
	$tableRows[$key]['exits'] = $value['exits'];



	$tableRows[$key]['clicks'] = $value['clicks'];
	$tableRows[$key]['avg / clicks'] = round($value['clicks'] / $users);

	$tableRows[$key]['moves'] = $value['moves'];
	$tableRows[$key]['avg / moves'] = round($value['moves'] / $users);

	$tableRows[$key]['scrolls'] = $value['scrolls'];
	$tableRows[$key]['avg / scrolls'] = round($value['scrolls'] / $users);

	$tableRows[$key]['move time'] = format_milliseconds($moves_time);
	$tableRows[$key]['avg / move time'] = format_milliseconds($moves_time / $users);
	$tableRows[$key]['scroll time'] = format_milliseconds($scrolls_time);
	$tableRows[$key]['avg / scroll time'] = format_milliseconds($scrolls_time / $users);
	$tableRows[$key]['spend time'] = format_milliseconds($spend_time);
	$tableRows[$key]['avg / spend time'] = format_milliseconds($spend_time / $users);

	// $url = US . DOMAIN . US . CONTROLLER . "?link={$value['id']}";
	// $records[$key]['url'] = "<a href=\"{$url}\">{$value['url']}</a>";
	$urlGetSite = $value['url'];
};

// $user_info = fill_table($records);
$user_info = fill_table($tableRows);



$data = "link={$get_link}";
$urlImageHeatMoves = "http://" . DOMAIN . US . "data" . US . "get_heat_moves?" . $data;
$urlGetData = "http://" . DOMAIN . US . "data" . US . "get_data?" . $data;
$urlGetSize = "http://" . DOMAIN . US . "data" . US . "get_size?" . $data;
$linkData = "getSize: '{$urlGetSize}', imageHeatMoves: '{$urlImageHeatMoves}', getSite: '{$urlGetSite}'";

$template = basename(__FILE__, EXT_PHP);
$user_info_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_info_content = $faTemplate->set($user_info_template, array(
	'|ITEM|' => $user_info,
	'|LINK_DATA|' => $linkData,
		));

return $user_info_content;
