<?php

$menu_statistic = array(
	// 'charts' => 'charts',
	'users' => 'users',
	'moves' => 'mouse moves',
	'' => 'back to links list',
);

if (METHOD == 'default') {
	$statistic_menu_selected = current($menu_statistic);
} else {
	$statistic_menu_selected = METHOD;
}


$menu_statistic_content = array();
foreach ($menu_statistic as $key => $value) {
	if ($key == '') {
		$menu_url = 'http://' . DOMAIN . US . APPLICATION;
	} else {
		$menu_url = 'http://' . DOMAIN . US . APPLICATION . US . $key . "?link={$get_link}&date_start={$get_date_start}&date_end={$get_date_end}";
	}
	$menu_title = $value;
	if ($key == $statistic_menu_selected) {
		$menu_statistic_content[] = "<span class=\"button active\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
	} else {
		$menu_statistic_content[] = "<span class=\"button\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
	}
}

$menu_statistic_content = implode(PHP_EOL, $menu_statistic_content);

$statistic_menu_template = $faTemplate->get('application' . DS . 'menu' . DS . 'menu_top');

$statistic_menu_result = $faTemplate->set($statistic_menu_template, array('|CONTENT|' => $menu_statistic_content,));

return $statistic_menu_result;
