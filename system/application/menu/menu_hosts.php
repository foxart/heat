<?php

$authorizationData = $faAuthorize->get_login();
// view($authorizationData);

$sql_select = "
		SELECT
			sh.id AS host_id, sh.host
		FROM
			statistic_host AS sh
		LEFT JOIN
			user_statistic_host AS ush ON ush.fk_host = sh.id
		WHERE
			IF(|USER| = 0, 1, ush.fk_user = |USER|)
	";
$faSql->load($sql_select);
$faSql->set(array(
	'USER' => $authorizationData['user_id']
));
$faSql->run();
$records = $faSql->to_assoc();



if ($faSql->num_rows > 0) {
	$menu_hosts = array();
	$menu_hosts[] = "<div class=\"o_h\" style=\"margin-bottom: 20px;\">";
	$menu_hosts[] = "<ul>";
	foreach ($records as $key => $value) {
		$host_id = $value['host_id'];
		$host = $value['host'];
		$menu_url = 'http://' . DOMAIN . US . APPLICATION . '?host=' . $host_id;
		$menu_title = $host;
		if ($host_id == $authorizationData['host_id']) {
			$menu_hosts[] = "<li><a class=\"fw_b fs_3\" href=\"{$menu_url}\">{$menu_title}</a></li>";
		} else {
			$menu_hosts[] = "<li><a class=\"fs_3\" href=\"{$menu_url}\">{$menu_title}</a></li>";
		};
	};
	$menu_hosts[] = "</ul>";
	$menu_hosts[] = "</div>";

	$menu_hosts = implode(PHP_EOL, $menu_hosts);
} else {
	$menu_hosts = false;
};

// view($menu_hosts);

$template = basename(__FILE__, EXT_PHP);
$list_hosts_template = $faTemplate->get('application' . DS . 'menu' . DS . $template);
$list_hosts_content = $faTemplate->set($list_hosts_template, array(
	'|MENU|' => $menu_hosts,
		));

return $list_hosts_content;
