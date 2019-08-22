<?php

$links_list = require_once PATH_APPLICATION . APPLICATION . DS . 'links_list' . EXT_PHP;

$template = basename(__FILE__, EXT_PHP);
$user_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_result = $faTemplate->set($user_template, array(
	'|LINKS_LIST|' => $links_list,
	'|GET_URL|' => $get_url,
		));

return $user_result;
