<?php

$paginatorLinksLimit = 15;
$paginatorActivityLimit = 15;

switch (METHOD) {
	case 'activity':
		$method = 'user_activity';
		break;
	case 'links':
		$method = 'user_links';
		break;
	// case 'chart':
		// $method = 'user_chart';
		// break;
	case 'chat':
		$method = 'user_chat';
		break;
	default:
		$method = 'user_activity';
		break;
}

$user_links = require_once PATH_APPLICATION . APPLICATION . DS . $method . EXT_PHP;
$user_menu = require_once PATH_APPLICATION . APPLICATION . DS . 'user_menu' . EXT_PHP;
$user_info = require_once PATH_APPLICATION . APPLICATION . DS . 'user_info' . EXT_PHP;

$template = basename(__FILE__, EXT_PHP);
$user_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_result = $faTemplate->set($user_template, array(
	'|DATE_START|' => $get_date_start,
	'|DATE_END|' => $get_date_end,
	'|USER_ID|' => $get_user,
	'|USER_MENU|' => $user_menu,
	'|USER_INFO|' => $user_info,
	'|USER_LINKS|' => $user_links,
		));

return $user_result;
