<?php
	
	$liveLimit = 10;
	
	
	$authorizationData = $faAuthorize->get_login();
	
	switch (METHOD) {
		// ajax
		case 'get_monitor':
			$application_content = require_once PATH_APPLICATION . 'statistic' . DS . 'get_monitor' . EXT_PHP;
			echo $application_content;
			exit;
			break;
			
		
		case 'monitor':
			$application_content = require_once PATH_APPLICATION . 'statistic' . DS . 'monitor' . EXT_PHP;
			break;
		// case 'chart':
			// $application_content = require_once PATH_APPLICATION . 'statistic' . DS . 'chart' . EXT_PHP;
			// break;
		case 'statistic':
			$application_content = require_once PATH_APPLICATION . 'statistic' . DS . 'statistic' . EXT_PHP;
			break;
		default:
			$application_content = require_once PATH_APPLICATION . 'statistic' . DS . 'monitor' . EXT_PHP;
		break;
	}
		
	
	// if (METHOD == 'monitor')
	// {
	// } else if (METHOD == 'statistic'){
		// $application_content = require_once PATH_APPLICATION . 'statistic' . DS . 'statistic' . EXT_PHP;
	// } else {
	// };

	$application_menu = require_once PATH_APPLICATION . 'statistic' . DS . 'menu' . EXT_PHP;

	$template = basename(__FILE__, EXT_PHP);
	$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . 'application');
	$application_result = $faTemplate->set($application_template, array(
		'|MENU|' => $application_menu,
		'|CONTENT|' => $application_content,
	));

	return $application_result;
?>