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
			WHERE
				IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
			GROUP BY
				sg.ip
		) AS tmp
	";
$faSql->load($sql_count);
$faSql->set(array(
	'HOST' => $authorizationData['host_id'],
));
$faSql->run();

$record = $faSql->fetch_assoc();
$count = $record['count'];

$paginatorOrder = 'ASC';
$paginator = new faPaginator();
$paginator->initialize(array(2, 3, 2), array('<<', '<', '...', '>', '>>'));
$paginator->create($get_page, $count, $paginatorLimit, $paginatorOrder);
$paginatorOffset = $paginator->offset;



$sql_select = "
		SET @row := 0;

		SELECT
			@row := @row + 1 AS 'row', tmp.*
		FROM
		(
			SELECT
				sg.id,
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
				statistic_geo AS sg ON sg.id = s.fk_statistic_geo
			LEFT JOIN
				geo_country AS gcountry ON gcountry.id = sg.fk_geo_country
			LEFT JOIN
				geo_city AS gcity on gcity.id = sg.fk_geo_city
			WHERE
				IF(|HOST| = 0, 1, s.fk_statistic_host = |HOST|)
			GROUP BY
				sg.id
			ORDER BY
				s.id
		) AS tmp
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
));
$faSql->run();

if ($faSql->num_rows > 0) {
	$records = $faSql->to_assoc();
	$tableRows = array();
	foreach ($records as $key => $value) {
		$flag = strtolower($value['code']);
		$user_id = $value['id'];
		$user_ip = $value['ip'];
		$url = 'http://' . DOMAIN . US . APPLICATION . "?user={$user_id}";
		$chatUrl = 'http://' . DOMAIN . US . APPLICATION . US . "chat?user={$user_id}";
		$tableRows[$key]['N'] = $value['row'];
		$tableRows[$key]['user'] = "<a class=\"\" href=\"{$url}\">{$user_ip}</a>";

//		$chatUrl = 'http://' . DOMAIN . US . 'users' . US . 'chat' . "?user={$user_id}";
//		$tableRows[$key]['chat'] = "<a class=\"\" href=\"{$chatUrl}\">chat</a>";

		$tableRows[$key]['chat'] = "<a class=\"\" href=\"{$chatUrl}\"><img src=\"/heat/theme/default/image/icons/user.chat.png\"/></a>";
		$tableRows[$key]['flag'] = "<span class=\"flag flag_{$flag}\">&nbsp;</span>";
		$tableRows[$key]['country'] = $value['country'];
		$tableRows[$key]['city'] = $value['city'];
		$tableRows[$key]['links'] = $value['links'];
		$tableRows[$key]['links unique'] = $value['links unique'];
		// $tableRows[$key]['animate'] = "<a class=\"fa_animate {data: '{$urlData}', image: '{$urlImage}'}\" href=\"{$value['url']}\"><img alt=\"{$urlThumbnail}\" src=\"{$urlThumbnail}\"></img></a>";
		$tableRows[$key]['enters'] = $value['enters'];
		$tableRows[$key]['exits'] = $value['exits'];
		$tableRows[$key]['clicks'] = $value['clicks'];
		$tableRows[$key]['moves'] = $value['moves'];
		$tableRows[$key]['scrolls'] = $value['scrolls'];
		$tableRows[$key]['move time'] = format_milliseconds($value['move_time']);
		$tableRows[$key]['scroll time'] = format_milliseconds($value['scroll_time']);
		$tableRows[$key]['spend time'] = format_milliseconds($value['spend_time']);
	}
	$users_list = fill_table($tableRows);
} else {
	$users_list = false;
}

$paginator = $paginator->parse($page_url);

$template = basename(__FILE__, EXT_PHP);
$users_list_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$users_list_content = $faTemplate->set($users_list_template, array(
	'|ITEMS|' => $users_list,
	'|PAGINATOR|' => $paginator,
		));

return $users_list_content;
