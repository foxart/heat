<?php

$statistic_limit = 5;

$statistic_order = 'DESC';
$statistic_most_users = require PATH_APPLICATION . APPLICATION . DS . 'statistic_users' . EXT_PHP;

$statistic_order = 'DESC';
$statistic_most_views = require PATH_APPLICATION . APPLICATION . DS . 'statistic_views' . EXT_PHP;

$statistic_order = 'DESC';
$statistic_most_enters = require PATH_APPLICATION . APPLICATION . DS . 'statistic_enters' . EXT_PHP;

$statistic_order = 'DESC';
$statistic_most_clicks = require PATH_APPLICATION . APPLICATION . DS . 'statistic_clicks' . EXT_PHP;

$statistic_order = 'DESC';
$statistic_most_moves = require PATH_APPLICATION . APPLICATION . DS . 'statistic_moves' . EXT_PHP;

$statistic_order = 'DESC';
$statistic_most_scrolls = require PATH_APPLICATION . APPLICATION . DS . 'statistic_scrolls' . EXT_PHP;

$template = basename(__FILE__, EXT_PHP);
$application_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$application_result = $faTemplate->set($application_template, array(
	'|USERS|' => $statistic_most_users,
	'|VIEWS|' => $statistic_most_views,
	'|ENTERS|' => $statistic_most_enters,
	'|CLICKS|' => $statistic_most_clicks,
	'|MOVES|' => $statistic_most_moves,
	'|SCROLLS|' => $statistic_most_scrolls,
		));

return $application_result;
