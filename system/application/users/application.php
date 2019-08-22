<?php

$authorizationData = $faAuthorize->get_login();
$getInput = filter_input_array(INPUT_GET);

if (isset($getInput['user']) == true) {
	$get_user = $getInput['user'];
	$get['user'] = $get_user;
}

if (isset($getInput['page']) == true) {
	$get_page = $getInput['page'];
} else {
	$get_page = 1;
}
$get['page'] = '|PAGE|';

if (count($get) > 0) {
	foreach ($get as $key => $value) {
		$get_tmp[] = "{$key}={$value}";
	}
	$page_url = '?' . implode('&amp;', $get_tmp);
} else {
	$page_url = '';
}

if (isset($getInput['user']) == true) {
	$application_content = require_once PATH_APPLICATION . APPLICATION . DS . 'user' . EXT_PHP;
} else {
	$application_content = require_once PATH_APPLICATION . APPLICATION . DS . 'users' . EXT_PHP;
}

$template = basename(__FILE__, EXT_PHP);
$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$application_result = $faTemplate->set($application_template, array(
	'|CONTENT|' => $application_content,
		));

return $application_result;
