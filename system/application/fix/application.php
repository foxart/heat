<?php
	
	ob_start();
	
	$application_result = require_once PATH_APPLICATION . 'fix' . DS . METHOD . EXT_PHP;
	
	$application_result = ob_get_clean();
	
	return $application_result;
	
?>