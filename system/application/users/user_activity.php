<?php

$sql_count = "
		SELECT
			COUNT(s.id) AS count
		FROM
			statistic AS s
		LEFT JOIN
			statistic_action AS sa ON sa.fk_statistic = s.id
		LEFT JOIN
			statistic_url AS su ON s.fk_statistic_url = su.id
		WHERE
			s.fk_statistic_geo = |USER|
		AND
			(sa.action = 'enter' OR sa.action = 'exit')
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
	";
$faSql->load($sql_count);
$faSql->set(array(
	// 'HOST' => $authorizationData['host_id'],
	'USER' => $get_user,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
));
$faSql->run();

// view($faSql);
// $count = $faSql->num_rows;
$records = $faSql->fetch_assoc();
$count = $records['count'];

// echo $count;

$paginatorOrder = 'ASC';
$paginator = new faPaginator();
$paginator->initialize(array(2, 3, 2), array('<<', '<', '...', '>', '>>'));
$paginator->create($get_page, $count, $paginatorActivityLimit, $paginatorOrder);
$paginatorOffset = $paginator->offset;



$sql_select = "
		SELECT
			sa.action,
			su.id,
			su.url,
			DATE_FORMAT(s.time, '%Y-%m-%d') AS date,
			DATE_FORMAT(s.time, '%T') AS time
			-- s.enter AS enters,
			-- s.exit AS exits,
			-- s.click AS clicks,
			-- s.scroll AS scrolls,
			-- s.move AS moves,
			-- s.move_time,
			-- s.scroll_time,
			-- s.spend_time AS spend_time
		FROM
			statistic AS s
		LEFT JOIN
			statistic_action AS sa ON sa.fk_statistic = s.id
		LEFT JOIN
			statistic_url AS su ON s.fk_statistic_url = su.id
		WHERE
			s.fk_statistic_geo = |USER|
		AND
			(sa.action = 'enter' OR sa.action = 'exit')
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
		ORDER BY
			sa.id DESC
		LIMIT
			|LIMIT|
		OFFSET
			|OFFSET|
	";
$faSql->load($sql_select);
$faSql->set(array(
	// 'HOST' => $authorizationData['host_id'],
	'USER' => $get_user,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
	'LIMIT' => $paginatorActivityLimit,
	'OFFSET' => $paginatorOffset,
));
$faSql->run();

if ($faSql->num_rows > 0) {
	$records = $faSql->to_assoc();
	$count = 0;
	$tableRows = array();
	foreach ($records as $key => $value) {
		$count++;

		$id = $value['id'];

		$linkUrl = "http://" . DOMAIN . US . 'links' . "?link={$id}";

		$urlSite = $value['url'];

		$urlSiteTitle = htmlspecialchars(substr($value['url'], 0, 60));

		$tableRows[$key]['N'] = $paginatorOffset + $count;
		$tableRows[$key]['date'] = $value['date'];
		$tableRows[$key]['time'] = $value['time'];
		$tableRows[$key]['url'] = "<a class=\"\" href=\"{$linkUrl}\" title=\"{$urlSite}\">{$urlSiteTitle}</a>";
		$tableRows[$key]['action'] = $value['action'];

		// $tableRows[$key]['enters'] = $value['enters'];
		// $tableRows[$key]['exits'] = $value['exits'];
		// $tableRows[$key]['clicks'] = $value['clicks'];
		// $tableRows[$key]['moves'] = $value['moves'];
		// $tableRows[$key]['scrolls'] = $value['scrolls'];
		// $tableRows[$key]['move time'] = format_milliseconds($value['move_time']);
		// $tableRows[$key]['scroll time'] = format_milliseconds($value['scroll_time']);
		// $tableRows[$key]['spend time'] = format_milliseconds($value['spend_time']);
	}

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
	'|ITEMS|' => $list_record,
	'|PAGINATOR|' => $list_paginator,
		));

return $user_links_content;
?>