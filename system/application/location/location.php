<?php
	$location_countries = require_once PATH_APPLICATION . APPLICATION . DS . 'location_countries' . EXT_PHP;
	$location_map = require_once PATH_APPLICATION . APPLICATION . DS . 'location_map' . EXT_PHP;

	$template = basename(__FILE__, EXT_PHP);
	$location_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
	$location_result = $faTemplate->set($location_template, array(
		'|LOCATION_COUNTRIES|' => $location_countries,
		'|LOCATION_MAP|' => $location_map,
		'|URL_ALL|' => 'http://' . DOMAIN . US . APPLICATION,
	));

	return $location_result;
?>