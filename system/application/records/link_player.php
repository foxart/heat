<?php

$_SERVER['heat'] = false;

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
	";
$faSql->load($sql_count);
$faSql->set(array(
	'LINK_ID' => $get_link,
	'USER_ID' => $get_user,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
));

$faSql->run();
$data = $faSql->fetch_assoc();
$records = "found: " . $data['count'];

$data = "link={$get_link}&date_start={$get_date_start}&date_end={$get_date_end}";

$urlImageTrack = "http://" . DOMAIN . US . "data" . US . "get_image_player?" . $data;
$urlImageHeatClicks = "http://" . DOMAIN . US . "data" . US . "get_heat_clicks?" . $data;
$urlImageHeatMoves = "http://" . DOMAIN . US . "data" . US . "get_heat_moves?" . $data;
$urlGetData = "http://" . DOMAIN . US . "data" . US . "get_data?" . $data;
$urlGetSize = "http://" . DOMAIN . US . "data" . US . "get_size?" . $data;
$urlGetSite = $value['url'];

$metadata = "{getData: '{$urlGetData}', getSize: '{$urlGetSize}', imageHeatClicks: '{$urlImageHeatClicks}', imageHeatMoves: '{$urlImageHeatMoves}', imageTrack: '{$urlImageTrack}', getSite: '{$urlGetSite}'}";

$linkBack = "http://" . DOMAIN . US . APPLICATION . US . "charts?link={$get_link}";

$template = basename(__FILE__, EXT_PHP);
$ips_info_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$ips_info_result = $faTemplate->set($ips_info_template, array(
	'{{ URL_BACK }}' => $linkBack,
	'{{ METADATA }}' => $metadata,
	'{{ RECORDS }}' => $records,
//	'|ITEMS|' => $link_users,
//	'|PAGINATOR|' => $paginator->parse($page_url),
		));

return $ips_info_result;
