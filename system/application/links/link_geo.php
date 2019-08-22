<?php

	$url_data = "http://" . DOMAIN . US . "data" . US . "get_location?link={$get_link}";
		
		
	// view($url_data);
		
	$template = basename(__FILE__, EXT_PHP);
	$users_geo_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
	$users_geo_result = $faTemplate->set($users_geo_template, array(
		'|URL_DATA|' => $url_data,
	));

	return $users_geo_result;
?>