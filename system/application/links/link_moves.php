<?php

$action = 'move';
$sql_count = "
		SELECT COUNT(s.id) AS count
		FROM
			statistic AS s
		LEFT JOIN
			statistic_action AS sa ON sa.fk_statistic = s.id
		WHERE
			IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
		AND
			IF(|USER_ID| = 0, 1, s.fk_statistic_geo = |USER_ID|)
		-- AND
		--	DATE_FORMAT(s.time, '%Y') = DATE_FORMAT(NOW(), '%Y')
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
		AND
			sa.action = '|ACTION|'
	";
$faSql->load($sql_count);
$faSql->set(array(
	'LINK_ID' => $get_link,
	'USER_ID' => $get_user,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
	'ACTION' => $action,
));

$faSql->run();

//view($faSql);

$data = $faSql->fetch_assoc();
$records = "found: " . $data['count'];


$data = "link={$get_link}&action={$action}&date_start={$get_date_start}&date_end={$get_date_end}";
$urlImageHeatMoves = "http://" . DOMAIN . US . "data" . US . "get_heat_moves?{$data}";
$urlGetData = "http://" . DOMAIN . US . "data" . US . "get_data?{$data}";
$urlGetSize = "http://" . DOMAIN . US . "data" . US . "get_size?{$data}";
$linkData = "getSize: '{$urlGetSize}', imageHeatMoves: '{$urlImageHeatMoves}', getSite: '{$urlGetSite}'";

$linkBack = "http://" . DOMAIN . US . APPLICATION . "?page={$get_page}";

$template = basename(__FILE__, EXT_PHP);
$user_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_result = $faTemplate->set($user_template, array(
	'{{ RECORDS }}' => $records,
	'|LINK_DATA|' => $linkData,
	'{{ URL_BACK }}' => $linkBack,
		));

return $user_result;
