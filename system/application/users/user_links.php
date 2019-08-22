<?php

$sql_count = "
		SELECT
			COUNT(s.id) AS count
		FROM
			statistic AS s
		LEFT JOIN
			statistic_geo AS sg ON sg.id = s.fk_statistic_geo
		LEFT JOIN
			statistic_url AS su ON su.id = s.fk_statistic_url
		WHERE
			IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
		AND
			IF(|USER| = 0, 1, sg.id = |USER|)
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
	";
$faSql->load($sql_count);
$faSql->set(array(
	'HOST' => $authorizationData['host_id'],
	'USER' => $get_user,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
));
$faSql->run();
// $count = $faSql->num_rows;
$records = $faSql->fetch_assoc();
$count = $records['count'];

$paginatorOrder = 'ASC';
$paginator = new faPaginator();
$paginator->initialize(array(2, 3, 2), array('<<', '<', '...', '>', '>>'));
$paginator->create($get_page, $count, $paginatorLinksLimit, $paginatorOrder);
$paginatorOffset = $paginator->offset;



$sql_select = "
		SELECT

			su.id AS id,
			sg.ip,
			DATE_FORMAT(s.time, '%Y-%m-%d') AS date,
			DATE_FORMAT(s.time, '%Y') AS year,
			DATE_FORMAT(s.time, '%m') AS month,
			DATE_FORMAT(s.time, '%d') AS day,
			su.url,
			s.enter AS enters,
			s.exit AS exits,
			s.click AS clicks,
			s.scroll AS scrolls,
			s.move AS moves,
			s.move_time,
			s.scroll_time,
			s.spend_time AS spend_time
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
			IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
		AND
			IF(|USER| = 0, 1, sg.id = |USER|)
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
		ORDER BY
			s.id DESC
		LIMIT
			|LIMIT|
		OFFSET
			|OFFSET|
	";
$faSql->load($sql_select);
$faSql->set(array(
	'HOST' => $authorizationData['host_id'],
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
	'USER' => $get_user,
	'LIMIT' => $paginatorLinksLimit,
	'OFFSET' => $paginatorOffset,
));
$faSql->run();

if ($faSql->num_rows > 0) {
	$records = $faSql->to_assoc();
	$tableRows = array();
	$count = 0;
	foreach ($records as $key => $value) {
		$count++;
		$id = $value['id'];

		$data = "link={$id}&amp;user={$get_user}";



		$urlImageTrack = "http://" . DOMAIN . US . "data" . US . "get_image?" . $data;
		$urlImageHeatClicks = "http://" . DOMAIN . US . "data" . US . "get_heat_clicks?" . $data;
		$urlImageHeatMoves = "http://" . DOMAIN . US . "data" . US . "get_heat_moves?" . $data;
		$urlGetData = "http://" . DOMAIN . US . "data" . US . "get_data?" . $data;
		$urlGetSize = "http://" . DOMAIN . US . "data" . US . "get_size?" . $data;
		$urlGetSite = $value['url'];


		$linkUrl = "http://" . DOMAIN . US . "links?link={$id}";




		$urlSite = $value['url'];
		// $urlSiteTitle = htmlspecialchars(substr($value['url'], 0, 50));
//		$titles = str_split($urlSite, 50);
//		$title = implode('<br/>', $titles);

		$title = $value['url'];
		$urlSiteTitle = htmlspecialchars(substr($value['url'], 0, 60));



		$tableRows[$key]['N'] = $paginatorOffset + $count;


		$tableRows[$key]['date'] = $value['date'];

//		$tableRows[$key]['heat'] = "<a class=\"fa_heat {getData: '{$urlGetData}', getSize: '{$urlGetSize}', imageHeatClicks: '{$urlImageHeatClicks}', imageHeatMoves: '{$urlImageHeatMoves}', imageTrack: '{$urlImageTrack}', getSite: '{$urlGetSite}'}\" href=\"#\">heat</a>";



		$tableRows[$key]['url'] = "<a class=\"\" href=\"{$linkUrl}\" title=\"{$title}\">{$urlSiteTitle}</a>";



		$tableRows[$key]['enters'] = $value['enters'];
		$tableRows[$key]['exits'] = $value['exits'];
		$tableRows[$key]['clicks'] = $value['clicks'];
		$tableRows[$key]['moves'] = $value['moves'];
		$tableRows[$key]['scrolls'] = $value['scrolls'];
		$tableRows[$key]['move time'] = format_milliseconds($value['move_time']);
		$tableRows[$key]['scroll time'] = format_milliseconds($value['scroll_time']);
		$tableRows[$key]['spend time'] = format_milliseconds($value['spend_time']);
	};

	$list_record = fill_table($tableRows);
} else {
	$list_record = '<h1>no records found</h1>';
};


$list_paginator = $paginator->parse($page_url);

$template = basename(__FILE__, EXT_PHP);
$user_links_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_links_content = $faTemplate->set($user_links_template, array(
	'|URL_BACK|' => 'http://' . DOMAIN . US . APPLICATION,
	'|USER_ID|' => $get_user,
	'|DATE_START|' => $get_date_start,
	'|DATE_END|' => $get_date_end,
	'|ITEMS|' => $list_record,
	'|PAGINATOR|' => $list_paginator,
		));

return $user_links_content;
?>