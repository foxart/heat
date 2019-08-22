<?php

$authorizationData = $faAuthorize->get_login();

$liveLimit = 15;

if (isset($getInput['user']) == true) {
	$get_user = $getInput['user'];
	$get['user'] = $get_user;
}


if (isset($_GET['statistic']) == true) {
	$get_statistic = $_GET['statistic'];
} else {
	$get_statistic = 0;
}

if (isset($_GET['action']) == true) {
	$get_action = $_GET['action'];
} else {
	$get_action = 0;
}

// echo METHOD;

if (METHOD == 'watch') {
	$application_content = require_once PATH_APPLICATION . 'live' . DS . 'watch' . EXT_PHP;
} else if (METHOD == 'get_live') {

	$application_content = require_once PATH_APPLICATION . 'live' . DS . 'get_live' . EXT_PHP;
	echo $application_content;
	exit;
} else if (METHOD == 'get_watch') {

	$application_content = require_once PATH_APPLICATION . 'live' . DS . 'get_watch' . EXT_PHP;
	echo $application_content;
	exit;
} else {
	$application_content = require_once PATH_APPLICATION . 'live' . DS . 'live' . EXT_PHP;
};

//	$application_content = false;

$link_href = 'http://' . DOMAIN . US . 'live' . US . 'ajax_live';

$application_menu = require_once PATH_APPLICATION . 'live' . DS . 'menu' . EXT_PHP;

$template = basename(__FILE__, EXT_PHP);
$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . 'application');
$application_result = $faTemplate->set($application_template, array(
	'|MENU|' => $application_menu,
	'|CONTENT|' => $application_content,
	'|URL|' => $link_href,
		));

return $application_result;
?>