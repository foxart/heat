<?php
	if (isset($_GET['country'])==true)
	{
		$get_country = $_GET['country'];
	} else {
		$get_country = 0;
	};
	$get['country'] = '|COUNTRY|';
	
	
	$authorizationData = $faAuthorize->get_login();
	
	$location_content = require_once PATH_APPLICATION . APPLICATION . DS . 'location' . EXT_PHP;


	$template = basename(__FILE__, EXT_PHP);
	$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
	$application_result = $faTemplate->set($application_template, array(
		'|CONTENT|' => $location_content,
	));

	return $application_result;
?>