<?php

// if(!ini_get('zlib.output_compression'))
// {
// echo 'enable gzip';
// exit;
// };

$js = array(
	// 'jquery',
	'html2canvas',
	'fa.track',
	'fa.chat.client',
);
$jsPath = PATH_PLUGIN;

$content = array();
foreach ($js as $item) {
	$content[] = file_get_contents($jsPath . $item . EXT_JS);
}

$result = implode('', $content);

// $resultGzip = gzencode($result, 9);
// $gz = gzopen('js.gz', 'w9');
// gzwrite($gz, $result);
// gzclose($gz);
// echo $resultGzip;

return $result;
