<?php

// $users_list = require_once PATH_APPLICATION . CONTROLLER . DS . 'users_list' . EXT_PHP;
// $image = US . DOMAIN . US . "data/get_image?link={$value['id']}&amp;user={$get_user}";
$url_data = "data/get_location";

// $records[$key]['ip'] = "<a class=\"fa_animate {data: '{$data}', image: '{$image}'}\" href=\"{$value['ip']}\">{$value['ip']}</a>";

$get_country = 0;

$map = require_once PATH_APPLICATION . "data" . DS . "get_location" . EXT_PHP;


$template = basename(__FILE__, EXT_PHP);
$users_geo_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$users_geo_result = $faTemplate->set($users_geo_template, array(
	'|URL_DATA|' => $url_data,
	'|MAP|' => $map,
		));

return $users_geo_result;
