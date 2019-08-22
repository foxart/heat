<?php

$authorizationData = $faAuthorize->get_login();



$chatDirectory = PATH_CACHE . 'chat';
$chatFile = $chatDirectory . DS . 'chat_' . $get_user . EXT_LOG;
$chatFileData = file($chatFile);
//view($chatFileData);
$content = array();
$line = 1;
foreach ($chatFileData as $key => $value) {
	$phpTime = mktime();

	$message = json_decode($value, true);
	$date = date('d M Y h:i:s', $message['time'] / 1000);

	if (isset($message['type']) == true) {
		$type = $message['type'];
	} else {
		$type = 'server';
	}

//	$date = DateTime::createFromFormat($message['time'] / 1000, 'd M Y h:i:s');

	$content[] = "<div class=\"chat\"><div class=\"{$type}\"><span class=\"number\">{$line}</span> <span class=\"user\">{$message['user']}</span> <span class=\"date\">{$date}</span> <div><pre class=\"message\">{$message['message']}</pre></div></div></div>";
	if ($type != 'system') {
		$line ++;
	}
}
$chatContent = implode(PHP_EOL, $content);

//$messages = array();
//$currLine = 0;
//$endLine = count($chatFileData) - 1;
//if ($currLine <= $endLine) {
//	while ($currLine <= $endLine) {
//		$messages[] = $chatFileData[$currLine];
//		$currLine++;
//	}
//	$message = json_encode($messages, true);
//}
//$result['line'] = count($chatFileData);
//$result['message'] = json_encode($messages, true);
//$result = json_encode($result);



$template = basename(__FILE__, EXT_PHP);
$user_links_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_links_content = $faTemplate->set($user_links_template, array(
	'|URL_BACK|' => 'http://' . DOMAIN . US . APPLICATION,
	'|CHAT_ID|' => $get_user,
	'{{ CHAT_HISTORY }}' => $chatContent,
	'|CHAT_USER|' => $authorizationData['user_login'],
	'|URL_CHAT|' => URL . 'api' . US . 'chat',
//		'|ITEMS|' => $list_record,
//		'|PAGINATOR|' => $list_paginator,
		));

return $user_links_content;
