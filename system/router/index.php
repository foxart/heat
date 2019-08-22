<?php

require_once 'statistic.start.php';

DEFINE('THEME', 'default');
require_once 'router_initialize' . EXT_PHP;

$url = parse_url(ltrim(rtrim($_SERVER['REQUEST_URI'], US), US));
$classes = explode(US, ltrim(rtrim($url['path'], US), US));
$class = $classes[0];
if (isset($classes[1]) == true) {
	$method = $classes[1];
} else {
	$method = 'default';
};

if ($class == '') {
	$controller = 'default';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	$application = 'links';
} else if ($class == 'data') {
	$controller = 'data';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	$application = 'data';
} else if ($class == 'heat') {
	$controller = 'heat';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	$application = 'heat';
} else if ($class == 'api') {
	$controller = 'api';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	$application = 'api';
} else if ($class == 'info') {
	$controller = 'info';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	$application = 'info';
	require_once $controller_path;
	exit;
} else if ($class == 'mysql') {
	$controller = 'mysql';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	require_once $controller_path;
	exit;
} else if ($class == 'pgsql') {
	$controller = 'pgsql';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	require_once $controller_path;
	exit;
} else if ($class == 'test') {
	$controller = 'test';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	require_once $controller_path;
	exit;
} else {
	/* 404 */
	$controller = 'default';
	$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	$application = $class;
	$application_path = PATH_APPLICATION . $class . DS . 'application' . EXT_PHP;
	if (file_exists($application_path) == false) {
		$application = '404';
	};
	/* 404 */
};

/* AUTHORIZATION */
if ($class != 'api') {
	if ($faAuthorize->check_login() == false) {
		$method = 'login';
		$controller = 'authorization';
		$controller_path = PATH_CONTROLLER . $controller . EXT_PHP;
	};
} else {

};
/* AUTHORIZATION */

require_once 'router_database' . EXT_PHP;
require_once 'router_url' . EXT_PHP;

DEFINE('CONTROLLER', $controller);
DEFINE('APPLICATION', $application);
DEFINE('METHOD', $method);
//echo $controller_path;
//exit;
$content = require_once $controller_path;
$statistic = require_once 'statistic.end.php';
echo str_replace('|STATISTIC|', $statistic, $content);
exit;
