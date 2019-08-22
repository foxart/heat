<?php
	
	$monitor_limit = 10;
	
	
	
	// $monitor_activity = require_once PATH_APPLICATION . APPLICATION . DS . 'monitor_activity' . EXT_PHP;
	
	$monitor_activity = '';
	$monitor_users = require_once PATH_APPLICATION . APPLICATION . DS . 'monitor_users' . EXT_PHP;
	$monitor_links = require_once PATH_APPLICATION . APPLICATION . DS . 'monitor_links' . EXT_PHP;
	
	
	
	$template = basename(__FILE__, EXT_PHP);
	$monitor_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
	$monitor_result = $faTemplate->set($monitor_template, array(
		'|ACTIVITY|' => $monitor_activity,
		'|LAST_LINKS|' => $monitor_links,
		'|LAST_USERS|' => $monitor_users,
	));

	return $monitor_result;
?>