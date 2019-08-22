<?php

$link = 'http://' . DOMAIN . US . 'statistic' . US . 'get_monitor';

$content = require_once PATH_APPLICATION . 'statistic' . DS . 'get_monitor' . EXT_PHP;

$template = basename(__FILE__, EXT_PHP);
$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . 'monitor');
$application_result = $faTemplate->set($application_template, array(
	'|CONTENT|' => $content,
	'|URL|' => $link,
		));

return $application_result;
