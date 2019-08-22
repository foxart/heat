<?php

$sql_count = "
		SELECT
			COUNT(tmp.id) AS count
		FROM
		(
			SELECT
				s.id
			FROM
				statistic AS s
			LEFT JOIN
				statistic_geo AS sg ON sg.id = s.fk_statistic_geo
			LEFT JOIN
				statistic_url AS su ON su.id = s.fk_statistic_url
			WHERE
				IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
			AND
				IF('|URL|' = '', 1, su.url LIKE '%|URL|%')
			GROUP BY
				s.fk_statistic_url
		) AS tmp
	";
$faSql->load($sql_count);
$faSql->set(array(
	'HOST' => $authorizationData['host_id'],
	'URL' => $get_url,
));
$faSql->run();
// $count = $faSql->num_rows;
$records = $faSql->fetch_assoc();
$count = $records['count'];

$paginator = new faPaginator();
$paginator->initialize(array(2, 3, 2), array('<<', '<', '...', '>', '>>'));

$paginatorOrder = 'ASC';
$paginator->create($get_page, $count, $paginatorLimit, $paginatorOrder);
$paginatorOffset = $paginator->offset;

$sql_select = "
		SELECT
			@row := |OFFSET|;
		SELECT
			@row := @row + 1 AS 'row', tmp.*
		FROM
		(
			SELECT
				s.id,
				s.fk_statistic_url,
				su.url,
				COUNT(sg.id) AS users,
				COUNT(DISTINCT sg.id) As 'users unique',
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
				IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
			AND
				IF('|URL|' = '', 1, su.url LIKE '%|URL|%')
			GROUP BY
				su.id
		) AS tmp
		-- ORDER BY
			-- tmp.id |ORDER|
		LIMIT
			|LIMIT|
		OFFSET
			|OFFSET|
	";

$faSql->load($sql_select);
$faSql->set(array(
	'HOST' => $authorizationData['host_id'],
	'LIMIT' => $paginatorLimit,
	'OFFSET' => $paginatorOffset,
	'URL' => $get_url,
		// 'ORDER' => $paginatorOrder,
));
$faSql->run();

if ($faSql->num_rows > 0) {
	$records = $faSql->to_assoc();
	$tableRows = array();
	foreach ($records as $key => $value) {

		$url = $value['url'];


		$title = $value['url'];
		$link_title = htmlspecialchars(substr($value['url'], 0, 60));

		$users = $value['users'];

		if ($users < 1) {
			$users = 1;
		}


		$moves_time = $value['move_time'];
		$scrolls_time = $value['scroll_time'];
		$spend_time = $value['spend_time'];
		$tableRows[$key]['N'] = $value['row'];


		$link_url = "http://" . DOMAIN . US . APPLICATION . US . "charts?link={$value['fk_statistic_url']}";
		$tableRows[$key]['link'] = "<a class=\"\" href=\"{$link_url}\" title=\"{$title}\">{$link_title}</a>";
		$link_moves_url = "http://" . DOMAIN . US . APPLICATION . US . "moves?link={$value['fk_statistic_url']}&page={$get_page}";
		$tableRows[$key]['moves map'] = "<a class=\"\" href=\"{$link_moves_url}\" title=\"moves map\"><img class=\"icon\" src=\"heat/theme/default/image/icons/moves.map.png\"></img></a>";
		$link_clicks_url = "http://" . DOMAIN . US . APPLICATION . US . "clicks?link={$value['fk_statistic_url']}&page={$get_page}";
		$tableRows[$key]['clicks map'] = "<a class=\"\" href=\"{$link_clicks_url}\" title=\"moves map\"><img class=\"icon\" src=\"heat/theme/default/image/icons/clicks.map.png\"></img></a>";
		$tableRows[$key]['users'] = $users;
		$tableRows[$key]['users unique'] = $value['users unique'];
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
	};
	$linksListTable = fill_table($tableRows);
} else {
	$linksListTable = '<h1>no records found</h1>';
}


$list_paginator = $paginator->parse($page_url);

$template = basename(__FILE__, EXT_PHP);
$ips_list_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$ips_list_result = $faTemplate->set($ips_list_template, array(
	'|ITEMS|' => $linksListTable,
	'|PAGINATOR|' => $list_paginator,
		));

return $ips_list_result;
