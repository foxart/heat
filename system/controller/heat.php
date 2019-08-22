<?php

$top_content = require_once PATH_APPLICATION . 'menu' . DS . 'menu_top' . EXT_PHP;
$hosts_content = require_once PATH_APPLICATION . 'menu' . DS . 'menu_hosts' . EXT_PHP;
$dates_content = require_once PATH_APPLICATION . 'menu' . DS . 'menu_dates' . EXT_PHP;
$head_content = require_once PATH_APPLICATION . 'head' . DS . 'application' . EXT_PHP;
$user_content = require_once PATH_APPLICATION . 'authorization' . DS . 'application' . EXT_PHP;

$default_content = require_once PATH_APPLICATION . APPLICATION . DS . 'application' . EXT_PHP;

/* STATISTIC */
$statistic = require_once PATH_ROUTER . 'statistic_end' . EXT_PHP;
/* STATISTIC */

$settings = parse_ini_file(PATH_ROUTER . 'router.ini');
$product_name = $settings['product_name'];
$product_url = $settings['product_url'];
$product_owner = $settings['product_owner'];
$product_version = $settings['product_version'];
$developer_name = $settings['developer_name'];
$developer_url = $settings['developer_url'];
$developer_email = $settings['developer_email'];
$developer_skype = $settings['developer_skype'];

$template_undefined = $faTemplate->get('heat');
$result = $faTemplate->set($template_undefined, array(
	'|PRODUCT_VERSION|' => $product_version,
	'|PRODUCT_URL|' => $product_url,
	'|PRODUCT_OWNER|' => $product_owner,
	'|PRODUCT_NAME|' => $product_name,
	'|DEVELOPER_SKYPE|' => $developer_skype,
	'|HEAD|' => $head_content,
	'|TOP|' => $top_content,
	'|HOSTS|' => $hosts_content,
	'|USER|' => $user_content,
	'|DATES|' => $dates_content,
	'|CONTENT|' => $default_content,
	'|STATISTIC|' => $statistic,
		));

return $result;
