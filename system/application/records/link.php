<?php

$link_menu = require_once PATH_APPLICATION . APPLICATION . DS . 'link_menu' . EXT_PHP;

$link_info = require_once PATH_APPLICATION . APPLICATION . DS . 'link_info' . EXT_PHP;

switch (METHOD) {
	case 'player':
		$link_users = require_once PATH_APPLICATION . APPLICATION . DS . 'link_player' . EXT_PHP;
		break;
	case 'users':
		$link_users = require_once PATH_APPLICATION . APPLICATION . DS . 'link_users' . EXT_PHP;
		break;
	case 'moves':
		$link_users = require_once PATH_APPLICATION . APPLICATION . DS . 'link_moves' . EXT_PHP;
		break;
	case 'clicks':
		$link_users = require_once PATH_APPLICATION . APPLICATION . DS . 'link_clicks' . EXT_PHP;
		break;
	case 'animate':
		$link_users = require_once PATH_APPLICATION . APPLICATION . DS . 'link_animate' . EXT_PHP;
		break;
	case 'pdf':
		$link_users = require_once PATH_APPLICATION . APPLICATION . DS . 'pdf' . EXT_PHP;
		break;
	default:
		$link_users = require_once PATH_APPLICATION . APPLICATION . DS . 'link_users' . EXT_PHP;
		break;
}

$template = basename(__FILE__, EXT_PHP);
$user_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_result = $faTemplate->set($user_template, array(
	'{{ RESET }}' => 'http://' . DOMAIN . US . APPLICATION . US . METHOD . "?link={$get_link}",
	'|URL_BACK|' => 'http://' . DOMAIN . US . APPLICATION,
	'|DATE_START|' => $get_date_start,
	'|DATE_END|' => $get_date_end,
	'|LINK_MENU|' => $link_menu,
	'|LINK_INFO|' => $link_info,
	'|LINK_ID|' => $get_link,
	'|CONTENT|' => $link_users,
		));

return $user_result;
