<?php

$sql_select = "
		SELECT
			sa.id, su.url
		FROM
			statistic
		LEFT JOIN
			statistic_url AS su ON su.id = statistic.fk_statistic_url
		LEFT JOIN
			statistic_action AS sa ON sa.fk_statistic = statistic.id
		LEFT JOIN
			statistic_geo ON statistic_geo.id = statistic.fk_statistic_geo

		WHERE
			statistic_geo.id = |STATISTIC_ID|
		ORDER BY
			sa.id DESC
		LIMIT 1
	";
$faSql->load($sql_select);
$faSql->set(array(
	'STATISTIC_ID' => $get_statistic,
));
$faSql->run();
$records = $faSql->fetch_assoc();
$site_url = $records['url'];
$action_id = $records['id'];
$statistic_id = $get_statistic;

$url = 'http://' . DOMAIN . US . 'live' . US . 'get_watch';

$content = require_once PATH_APPLICATION . 'live' . DS . 'get_watch' . EXT_PHP;

$template = basename(__FILE__, EXT_PHP);
$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . 'watch');
$application_result = $faTemplate->set($application_template, array(
//	'|CONTENT|' => $content,
	'|URL|' => $url,
	'|SITE_URL|' => $site_url,
	'|STATISTIC|' => $statistic_id,
	'|ACTION|' => $action_id,
//	'|CHAT_ID|' => $get_user,
	'|CHAT_ID|' => $statistic_id,
	'|CHAT_USER|' => $authorizationData['user_login'],
	'|URL_CHAT|' => URL . 'api' . US . 'chat',
		));


return $application_result;
