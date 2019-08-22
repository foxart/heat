<?php

/* SEPARATORS */
define('DS', DIRECTORY_SEPARATOR);
define('US', '/');

/* PROTOCOL */

//define('PROTOCOL', filter_input(INPUT_SERVER, 'REQUEST_SCHEME') . '://'); // only for PHP >= 5.6
if (filter_input(INPUT_SERVER, 'HTTPS') === 'on') {
	define('PROTOCOL', 'https://');
} else {
	define('PROTOCOL', 'http://');
}

define('DOMAIN', filter_input(INPUT_SERVER, 'HTTP_HOST'));
define('FOLDER', 'heat');

/* PATH AND URL */
define('PATH', getcwd() . DS);
define('URL', PROTOCOL . DOMAIN . US);

/* DIRECTORY STRUCTURE */



if (DOMAIN === 'heat.comodo.net') {
	define('PATH_CACHE', '/var/www/heat.comodo.net/cache/');
} elseif (DOMAIN === 'heat.comodo.net.dev') {
	define('PATH_CACHE', '/var/www/heat.comodo.net.dev/cache/');
} else {
	define('PATH_CACHE', PATH . 'cache' . DS);
}
	
// echo PATH_CACHE;
// exit;



define('PATH_PLUGIN', PATH . 'plugin' . DS);
define('PATH_STORAGE', PATH . 'storage' . DS);
define('PATH_SYSTEM', PATH . 'system' . DS);
define('PATH_THEME', PATH . 'theme' . DS);

/* SYSTEM STRUCTURE */
define('PATH_APPLICATION', PATH_SYSTEM . 'application' . DS);
//define('PATH_CONFIG', PATH_SYSTEM . 'config' . DS);
define('PATH_CONTROLLER', PATH_SYSTEM . 'controller' . DS);
define('PATH_CORE', PATH_SYSTEM . 'core' . DS);
define('PATH_LIBRARY', PATH_SYSTEM . 'library' . DS);
define('PATH_ROUTER', PATH_SYSTEM . 'router' . DS);

/* EXTENSIONS */
define('EXT_LOG', '.log');
define('EXT_CSS', '.css');
define('EXT_JS', '.js');
define('EXT_PHP', '.php');
define('EXT_TEMPLATE', '.tpl');
