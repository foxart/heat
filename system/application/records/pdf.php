<?php

function px2mm($px) {
//	return $px * 25.4 / 72;
	$result = $px * 0.278;
	return $result;
}


// $get_action = 'move';
// $json = require_once PATH_APPLICATION . 'data' . DS . 'get_size' . EXT_PHP;
// $size = json_decode($json, true);
// require_once PATH_APPLICATION . 'data' . DS . 'get_moves_image' . EXT_PHP;


$urlImageMoves = 'http://' . DOMAIN . US . 'heat' . US . 'cache' . US . $get_link . '_site_moves.jpg';

$template = basename(__FILE__, EXT_PHP);
$user_template = $faTemplate->get('application' . DS . APPLICATION . DS . $template);
$user_result = $faTemplate->set($user_template, array(
	'{{ URL IMAGE MOVES }}' => $urlImageMoves,
		));

// echo $user_result;
// exit;

$getWidth = $_GET['width'];
$getHeight = $_GET['height'];

require_once PATH_PLUGIN . 'html2pdf' . DS . 'html2pdf.class.php';

$content = $user_result;
$width = px2mm($getWidth);
$height = px2mm($getHeight);

try {
	$html2pdf = new HTML2PDF('L', array($width, $height), 'en');
//      $html2pdf->setModeDebug();
//	$html2pdf->setDefaultFont('Arial');
	$html2pdf->writeHTML($content);
	$html2pdf->Output("{$get_link}moves.pdf");
//	$result = $html2pdf->Output("{$get_link}moves.pdf", 'F');
//	header("Content-type:application/pdf");
//	echo file_get_contents("{$get_link}moves.pdf");
} catch (HTML2PDF_exception $e) {
	echo $e;
	exit;
}

exit;
