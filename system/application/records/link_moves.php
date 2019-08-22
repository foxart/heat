<?php

$_SERVER['heat'] = false;

$action = 'move';
$sql_count = "
		SELECT COUNT(s.id) AS count
		FROM
			statistic AS s
		LEFT JOIN
			statistic_action AS sa ON sa.fk_statistic = s.id
		WHERE
			IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
		-- AND
			-- IF(|USER_ID| = 0, 1, s.fk_statistic_geo = |USER_ID|)
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
$data = $faSql->fetch_assoc();
$records = "found: " . $data['count'];

$linkData = "link={$get_link}&action={$action}&date_start={$get_date_start}&date_end={$get_date_end}";
$urlImageSite = "http://" . DOMAIN . US . "heat" . US . "cache" . US . "{$get_link}.jpg";
$urlImageHeatMoves = "http://" . DOMAIN . US . "data" . US . "get_heat_moves?{$linkData}";
$urlGetData = "http://" . DOMAIN . US . "data" . US . "get_data?{$linkData}";
$urlGetSize = "http://" . DOMAIN . US . "data" . US . "get_size?{$linkData}";
$linkMetadata = "{getSize: '{$urlGetSize}', imageHeatMoves: '{$urlImageHeatMoves}', imageSite: '{$urlImageSite}', getSite: '{$urlGetSite}'}";

$linkBack = "http://" . DOMAIN . US . APPLICATION . US . "charts?{$linkData}";



$get_action = 'move';
$json = require_once PATH_APPLICATION . 'data' . DS . 'get_size' . EXT_PHP;
$size = json_decode($json, true);
require_once PATH_APPLICATION . 'data' . DS . 'get_moves_image' . EXT_PHP;
$urlImageMoves = 'http://' . DOMAIN . US . 'heat' . US . 'cache' . US . $get_link . '_site_moves.jpg';

$linkPdf = "http://" . DOMAIN . US . APPLICATION . US . "pdf?{$linkData}&width={$size['width']}&height={$size['height']}";

$template = basename(__FILE__, EXT_PHP);
$user_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_result = $faTemplate->set($user_template, array(
	'{{ METADATA }}' => $linkMetadata,
	'{{ RECORDS }}' => $records,
	'{{ URL_IMAGE_MOVES }}' => $urlImageMoves,
	'{{ URL_PDF }}' => $linkPdf,
	'{{ URL_BACK }}' => $linkBack,
		));

return $user_result;
