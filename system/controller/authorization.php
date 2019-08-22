<?php

$head = require_once PATH_APPLICATION . 'head' . DS . 'application' . EXT_PHP;
$menu = require_once PATH_APPLICATION . 'menu' . DS . 'menu_top' . EXT_PHP;
// $dates = require_once PATH_APPLICATION . 'menu' . DS . 'menu_dates' . EXT_PHP;

$content = require_once PATH_APPLICATION . 'authorization' . DS . 'application' . EXT_PHP;

$settings = parse_ini_file(PATH_ROUTER . 'router.ini');
$product_name = $settings['product_name'];
$product_url = $settings['product_url'];
$product_owner = $settings['product_owner'];
$product_version = $settings['product_version'];
$developer_name = $settings['developer_name'];
$developer_url = $settings['developer_url'];
$developer_email = $settings['developer_email'];
$developer_skype = $settings['developer_skype'];

$template = basename(__FILE__, EXT_PHP);
$template_undefined = $faTemplate->get('controller' . DS . $template);
$result = $faTemplate->set($template_undefined, array(
	'|PRODUCT_VERSION|' => $product_version,
	'|PRODUCT_URL|' => $product_url,
	'|PRODUCT_OWNER|' => $product_owner,
	'|PRODUCT_NAME|' => $product_name,
	'|DEVELOPER_SKYPE|' => $developer_skype,
	'|HEAD|' => $head,
	'|MENU|' => $menu,
	'|CONTENT|' => $content,
		));

return $result;
