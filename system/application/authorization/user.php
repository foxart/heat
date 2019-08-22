<?php

$authorizationData = $faAuthorize->get_login();
$time = date("H:i:s", $authorizationData['time']);

$template = basename(__FILE__, EXT_PHP);
$authorization_user_template = $faTemplate->get('application' . DS . 'authorization' . DS . $template);
$authorization_user_result = $faTemplate->set($authorization_user_template, array(
	'|USER|' => $authorizationData['user_login'],
	'|TIME|' => $time,
	'|URL_LOGOUT|' => 'http://' . DOMAIN . US . 'authorization' . US . 'logout',
		));

return $authorization_user_result;
