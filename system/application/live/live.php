<?php

		
	$link = 'http://' . DOMAIN . US . 'live' . US . 'get_live';

	$content = require_once PATH_APPLICATION . 'live' . DS . 'get_live' . EXT_PHP;
	
	$template = basename(__FILE__, EXT_PHP);
	$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . 'live');
	$application_result = $faTemplate->set($application_template, array(
		'|CONTENT|' => $content,
		'|URL|' => $link,
	));

	return $application_result;
?>