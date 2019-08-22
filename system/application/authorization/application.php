<?php
	if (METHOD == 'login')
	{
		$application_result = require_once PATH_APPLICATION . 'authorization' . DS . 'login' . EXT_PHP;
	} else if (METHOD == 'logout') {	
		require_once PATH_APPLICATION . 'authorization' . DS . 'logout' . EXT_PHP;
		exit;
	} else {
		$application_result = require_once PATH_APPLICATION . 'authorization' . DS . 'user' . EXT_PHP;
	};
	return $application_result;
?>