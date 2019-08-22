<?php

$paginatorLimit = 15;

$users_list = require_once PATH_APPLICATION . APPLICATION . DS . 'users_list' . EXT_PHP;
// $users_geo = require_once PATH_APPLICATION . APPLICATION . DS . 'users_geo' . EXT_PHP;

$template = basename(__FILE__, EXT_PHP);
$user_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_result = $faTemplate->set($user_template, array(
	// '|USERS_GEO|' => $users_geo,
	'|USERS_LIST|' => $users_list,
		));

return $user_result;
