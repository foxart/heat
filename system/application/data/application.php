<?php

if (isset($_GET['country']) == true) {
	$get_country = $_GET['country'];
} else {
	$get_country = 0;
}
$get['country'] = '|COUNTRY|';


if (isset($_GET['action']) == true) {
	$get_action = $_GET['action'];
} else {
	$get_action = '';
}



if (METHOD == 'get_data' or METHOD == 'get_image' or METHOD == 'get_image_player' or METHOD == 'get_heat_clicks' or METHOD == 'get_heat_moves' or METHOD == 'get_location' or METHOD == 'get_size' or METHOD == 'get_live' or METHOD == 'get_moves_image') {
	$apiResult = require_once PATH_APPLICATION . APPLICATION . DS . METHOD . EXT_PHP;
} else {
	$apiResult = require_once PATH_APPLICATION . APPLICATION . DS . 'default' . EXT_PHP;
}


return $apiResult;
