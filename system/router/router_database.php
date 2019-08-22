<?php

/* DATABASE */
$hosts = array(
	'heat.ivanko.dev' => array(
		'host' => 'csql',
		'database' => 'heat',
		'user' => 'heatcomodo',
		'password' => 'veRdGaj8',
	),
	'heat.comodo.net.wo' => array(
		'host' => 'localhost',
		'database' => 'heat',
		'user' => 'root',
		'password' => '',
	),
	'heat.comodo.net.local' => array(
		'host' => 'localhost',
		'database' => 'heat',
		'user' => 'root',
		'password' => '',
	),
	'heat.comodo.net.dev' => array(
		'host' => 'csql',
		'database' => 'heat',
		'user' => 'heatcomodo',
		'password' => 'veRdGaj8',
	),
	'heat.comodo.net' => array(
		'host' => 'csql',
		'database' => 'heat',
		'user' => 'heatcomodo',
		'password' => 'veRdGaj8',
	),
);

$faHost = $hosts[DOMAIN]['host'];
$faUser = $hosts[DOMAIN]['user'];
$faPassword = $hosts[DOMAIN]['password'];
$faDatabase = $hosts[DOMAIN]['database'];

$faSql->set_connection($faHost, $faUser, $faPassword, $faDatabase);
$faSql->connect();
/* DATABASE */