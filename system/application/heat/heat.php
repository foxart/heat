<?php

	$get_link = 2;
	$get_user = 1;
	// $data = require_once PATH_APPLICATION . 'data' . DS . 'data' . EXT_PHP;
	
	$paginatorLimit = 30;

	// $links_list = require_once PATH_APPLICATION . APPLICATION . DS . 'links_list' . EXT_PHP;

	// $heatContent = view($data, false);
	$heatContent = 'test';
	
	$template = basename(__FILE__, EXT_PHP);
	$heatTemplate = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
	// echo $heatContent;
	$heatResult = $faTemplate->set($heatTemplate, array(
		// '|LINKS_LIST|' => $links_list,
		'|HEAT_CONTENT|' => $heatContent,
	));

	return $heatResult;
?>