<?php

	$menu_statistic = array(
		'links' => 'links',
//		'users' => 'users',
//		'statistic' => 'statistic',
	);

	if (METHOD == 'default') {
		$statistic_menu_selected = current($menu_statistic);
	} else {
		$statistic_menu_selected = METHOD;
	};

	$menu_statistic_content = array();
	foreach ($menu_statistic as $key => $value) {
		$menu_url = 'http://' . DOMAIN . US . APPLICATION . US . $key;
		$menu_title = $value;
		if ($key == $statistic_menu_selected) {
			$menu_statistic_content[] = "<span class=\"button active\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
		} else {
			$menu_statistic_content[] = "<span class=\"button\"><a href=\"{$menu_url}\">{$menu_title}</a></span>";
		};
	};
	$menu_statistic_content = implode(PHP_EOL, $menu_statistic_content);

	$statistic_menu_template = $faTemplate->get('application' . DS . 'menu' . DS . 'menu_top');
	$statistic_menu_result = $faTemplate->set($statistic_menu_template, array(
		'|CONTENT|' => $menu_statistic_content,
	));

	return $statistic_menu_result;
?>