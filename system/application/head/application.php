<?php

$metaData = array(
	'title' => 'title',
);
$metaTemplate = '
	<title>|TITLE|</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="pragma" content="no-cache"/>
	<meta name="description" content=""/>
	<meta name="keywords" content=""/>
	<meta name="language" content="ru"/>
';
$metaSearch = array_keys($metaData);
$metaReplace = array_values($metaData);
array_walk($metaSearch, 'to_pattern');
$meta = str_replace($metaSearch, $metaReplace, $metaTemplate);


$headCss = array();
$headJs = array();



/**
 * THEME DATA
 */
$themeCssTemplate = '<link href="' . URL . FOLDER . US . 'theme' . US . THEME . US . 'css' . US . '|CSS|' . EXT_CSS . '" charset="utf-8" rel="stylesheet" type="text/css"/>';
$themeJsTemplate = '<script src="' . URL . FOLDER . US . 'theme' . US . THEME . US . 'js' . US . '|JS|' . EXT_JS . '" charset="utf-8" type="text/javascript"></script>';

$themeData = parse_ini_file(PATH_THEME . THEME . DS . 'theme.ini', true);
foreach ($themeData as $themeDataKey => $themeDataValue) {
	foreach ($themeDataValue as $key => $value) {
		if ($themeDataKey == 'css') {
			$headCss[] = str_replace('|CSS|', $value, $themeCssTemplate);
		}
		if ($themeDataKey == 'js') {
			$headJs[] = str_replace('|JS|', $value, $themeJsTemplate);
		}
	}
}

$coreCssTemplate = '<link href="' . URL . FOLDER . US . 'cache' . US . 'fa' . EXT_CSS . '" charset="utf-8" rel="stylesheet" type="text/css"/>';
$coreJsTemplate = '<script src="' . URL . FOLDER . US . 'cache' . US . 'fa' . EXT_JS . '" charset="utf-8" type="text/javascript"></script>';

/**
 * CORE DATA
 */
$coreData = array(
	'css' => array(
		'fa',
		'fa.admin',
		'fa.button',
		'fa.chart',
		'fa.content',
		'fa.flags',
		'fa.form',
		'fa.paginator',
		'fa.table',
		'fa.track',
	),
	'js' => array(
//		'jquery',
//		'jquery.metadata',
//		'jquery.livequery',
//		'jquery.canvasjs',
		'fa',
		'fa.animate',
		'fa.heat',
		'fa.chat.server',
//		'fa.chat.client',
//		'fa.track'
	)
);

$coreCss = array();
$coreJs = array();
foreach ($coreData as $coreDataKey => $coreDataValue) {
	foreach ($coreDataValue as $key => $value) {
		if ($coreDataKey == 'css') {
			$coreCss[] = file_get_contents(PATH_CORE . 'css' . DS . $value . EXT_CSS);
		}
		if ($coreDataKey == 'js') {
			$coreJs[] = file_get_contents(PATH_CORE . 'js' . DS . $value . EXT_JS);
		}
	}
}
file_put_contents(PATH_CACHE . 'fa' . EXT_CSS, implode(PHP_EOL, $coreCss));
file_put_contents(PATH_CACHE . 'fa' . EXT_JS, implode(PHP_EOL, $coreJs));
$headCss[] = $coreCssTemplate;
$headJs[] = $coreJsTemplate;








/**
 * COMPOSER
 */
$head = array();
$head[] = $meta;
$head[] = implode(PHP_EOL, $headCss);
$head[] = implode(PHP_EOL, $headJs);
$result = implode(PHP_EOL, $head);

return $result;
