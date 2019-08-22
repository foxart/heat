<?php

$menu_top = array(
	'statistic' => 'live',
//	'links' => 'mouse moves',
//	'records' => 'screen records',
	'records' => 'heat maps',
//	'users' => 'users',
	'location' => 'locations',
//	'live' => 'live',
);

if (APPLICATION == 'default') {
	$statistic_menu_selected = current($menu_top);
} else {
	$statistic_menu_selected = APPLICATION;
}

$menu_top_content = array();
foreach ($menu_top as $key => $value) {
	$menu_url = 'http://' . DOMAIN . US . $key;
	$menu_title = $value;
	if ($key == $statistic_menu_selected) {
		$menu_top_content[] = "<span class=\"button active\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
	} else {
		$menu_top_content[] = "<span class=\"button\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
	}
}

$menu_top_content[] = "<span class=\"button fl_r\"><a href=\"/version\">version history</a></span>";
$menu_top_content[] = "<span class=\"button fl_r\">&nbsp;</span>";
$menu_top_content[] = "<span class=\"button fl_r\"><a href=\"/product-map\">product map</a></span>";

$menu_top_content = implode(PHP_EOL, $menu_top_content);


$menu_template = $faTemplate->get('application' . DS . 'menu' . DS . 'menu_top');

$result = $faTemplate->set($menu_template, array(
	'|CONTENT|' => $menu_top_content
		));

return $result;
