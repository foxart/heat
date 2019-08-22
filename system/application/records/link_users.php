<?php

$_SERVER['heat'] = false;

$sql_count = "
	SELECT
		COUNT(s.id) AS count
	FROM
		statistic AS s
	WHERE
		IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
	AND
		IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
	AND
		IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
";
$faSql->load($sql_count);
$faSql->set(array(
	'LINK_ID' => $get_link,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
));
$faSql->run();
$contRecords = $faSql->fetch_assoc();
// $count = $faSql->num_rows;
$count = $contRecords['count'];
$paginator = new faPaginator();
$paginator->initialize(array(2, 3, 2), array('<<', '<', '...', '>', '>>'));
$paginatorOrder = 'ASC';
$paginator->create($get_page, $count, $paginatorLimit, $paginatorOrder);
$paginatorOffset = $paginator->offset;

$sql_select = "
	SELECT
		DATE_FORMAT(s.time, '%Y-%m-%d') AS date,
		DATE_FORMAT(s.time, '%Y') AS year,
		DATE_FORMAT(s.time, '%m') AS month,
		DATE_FORMAT(s.time, '%d') AS day,
		sg.id,
		sg.ip,
		gcountry.code,
		gcountry.country,
		gcity.city,
		s.enter AS enters,
		s.exit AS exits,
		s.click AS clicks,
		s.move AS moves,
		s.scroll AS scrolls,
		s.move_time,
		s.scroll_time,
		s.spend_time,
		su.url
	FROM
		statistic AS s
	LEFT JOIN
		statistic_geo AS sg ON sg.id = s.fk_statistic_geo
	LEFT JOIN
		statistic_url AS su ON su.id = s.fk_statistic_url
	LEFT JOIN
		geo_country AS gcountry ON gcountry.id = sg.fk_geo_country
	LEFT JOIN
		geo_city AS gcity on gcity.id = sg.fk_geo_city
	WHERE
		IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
	AND
		IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
	AND
		IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
	ORDER BY
		s.id |ORDER|
	LIMIT
		|LIMIT|
	OFFSET
		|OFFSET|

";
$faSql->load($sql_select);
$faSql->set(array(
	'LINK_ID' => $get_link,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
	'LIMIT' => $paginatorLimit,
	'OFFSET' => $paginatorOffset,
	'ORDER' => 'DESC',
));
$faSql->run();
$records = $faSql->to_assoc();

if ($faSql->num_rows > 0) {
	$count = 0;
	$tableRows = array();
	foreach ($records as $key => $value) {
		$count++;
		$id = $value['id'];
		$ip = $value['ip'];
		$flag = strtolower($value['code']);

		$data = "link={$get_link}&amp;user={$id}";

		$urlImageTrack = "http://" . DOMAIN . US . "data" . US . "get_image?" . $data;
		$urlImageHeatClicks = "http://" . DOMAIN . US . "data" . US . "get_heat_clicks?" . $data;
		$urlImageHeatMoves = "http://" . DOMAIN . US . "data" . US . "get_heat_moves?" . $data;
		$urlGetData = "http://" . DOMAIN . US . "data" . US . "get_data?" . $data;
		$urlGetSize = "http://" . DOMAIN . US . "data" . US . "get_size?" . $data;
		$urlGetSite = $value['url'];

		$userUrl = "http://" . DOMAIN . US . "users?user={$id}";
		$chatUrl = 'http://' . DOMAIN . US . 'users' . US . 'chat' . "?user={$id}";

		$tableRows[$key]['N'] = $paginatorOffset + $count;
		$tableRows[$key]['date'] = $value['date'];

//		$tableRows[$key]['heat'] = "<a class=\"fa_heat {getData: '{$urlGetData}', getSize: '{$urlGetSize}', imageHeatClicks: '{$urlImageHeatClicks}', imageHeatMoves: '{$urlImageHeatMoves}', imageTrack: '{$urlImageTrack}', getSite: '{$urlGetSite}'}\" href=\"#\">heat</a>";

		$tableRows[$key]['user'] = "<a class=\"\" href=\"{$userUrl}\">{$ip}</a>";
		$tableRows[$key]['chat'] = "<a class=\"\" href=\"{$chatUrl}\"><img src=\"/heat/theme/default/image/icons/user.chat.png\"/></a>";

		$tableRows[$key]['flag'] = "<span class=\"flag flag_{$flag}\">&nbsp;</span>";
		$tableRows[$key]['country'] = $value['country'];
		$tableRows[$key]['city'] = $value['city'];

		$tableRows[$key]['enters'] = $value['enters'];
		$tableRows[$key]['exits'] = $value['exits'];
		$tableRows[$key]['clicks'] = $value['clicks'];
		$tableRows[$key]['moves'] = $value['moves'];
		$tableRows[$key]['scrolls'] = $value['scrolls'];

		$tableRows[$key]['move time'] = format_milliseconds($value['move_time']);
		$tableRows[$key]['scroll time'] = format_milliseconds($value['scroll_time']);
		$tableRows[$key]['spend time'] = format_milliseconds($value['spend_time']);
	}
	$link_users = fill_table($tableRows);
} else {
	$link_users = '<h1>no records found</h1>';
}


$template = basename(__FILE__, EXT_PHP);
$ips_info_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$ips_info_result = $faTemplate->set($ips_info_template, array(
	'|URL_BACK|' => APPLICATION,
	'|ITEMS|' => $link_users,
	'|PAGINATOR|' => $paginator->parse($page_url),
		));

return $ips_info_result;
