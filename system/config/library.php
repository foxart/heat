<?php

$libraries = array(
	'fa.function',
	'fa.cryptography',
	'fa.io',
	'fa.image',
	'fa.template',
	'fa.sql',
	'fa.paginator',
	'fa.authorize',
);

foreach ($libraries as $library) {
	require_once PATH_LIBRARY . $library . EXT_PHP;
};

// view($libraries);


