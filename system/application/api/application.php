<?php

// echo @$_GET['heatapi'];
// exit;
// $authorizationData = $faAuthorize->get_login();
// if ($faAuthorize->check_login() == true)
// {
// $result = $authorizationData['user_login'];
// return $result;
// exit;
// };


if (METHOD == 'check' or METHOD == 'js' or METHOD == 'image' or METHOD == 'log' or METHOD == 'chat') {
	$apiResult = require_once PATH_APPLICATION . APPLICATION . DS . METHOD . EXT_PHP;
} else {
	$apiResult = require_once PATH_APPLICATION . APPLICATION . DS . 'default' . EXT_PHP;
}

// return view($authorizationData, false);



return $apiResult;
