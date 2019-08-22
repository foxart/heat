<?php

$menu_statistic = array(
	// 'chart' => 'chart',
	'links' => 'links',
	'activity' => 'activity',
	'chat' => 'chat',
	'' => 'back to users list',
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
		$menu_url = 'http://' . DOMAIN . US . APPLICATION . US . $key . "?user={$get_user}";
	}

	$menu_title = $value;
	if ($key == $statistic_menu_selected) {
		$menu_statistic_content[] = "<span class=\"button active\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
	} else {
		$menu_statistic_content[] = "<span class=\"button\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
	}
}
$content = implode(PHP_EOL, $menu_statistic_content);

$statistic_menu_template = $faTemplate->get('application' . DS . 'menu' . DS . 'menu_top');
$statistic_menu_result = $faTemplate->set($statistic_menu_template, array(
	'|CONTENT|' => $content,
		));

return $statistic_menu_result;
