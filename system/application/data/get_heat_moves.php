<?php

// $sql_select =
// "
// SELECT
// su.image, su.width, su.height
// FROM
// statistic_url AS su
// WHERE
// su.id = |ID|
// ";
// $faSql->load($sql_select);
// $faSql->set(array(
// 'ID' => $get_link
// ));
// $faSql->run();
// $result = $faSql->fetch_assoc();
// $imageName = $result['image'] . '.jpg';
// $imageWidth = $result['width'];
// $imageHeight = $result['height'];
// $imagePath = PATH_LOG . $result['image'] . '.jpg';
// $imageUrl = 'http://' . DOMAIN . US . 'log' . US . $result['image'] . '.jpg';

$resultJson = require_once 'get_size' . EXT_PHP;
$result = json_decode($resultJson, true);


$imageWidth = $result['width'];
$imageHeight = $result['height'];


$dataJson = require_once 'get_data' . EXT_PHP;
$data = json_decode($dataJson, true);


/*
 * load image
 */

$imageSiteFilename = PATH_CACHE . 'screenshots' . DS . $get_link . '.jpg';
$imageTrackFilename = PATH_CACHE . 'screenshots' . DS . $get_link . '.png';

//$imageSite = new faImage();
//$imageSite->load($imageSiteFilename);
//$imageSite->resize($imageWidth, $imageHeight);


$imageTrack = new faImage();
$imageTrack->create($imageWidth, $imageHeight);

$maxPointsRed = 0;
$minPointsRed = 0;
$maxPointsYellow = 0;
$minPointsYellow = 0;

$precisionRed = 50;
$precisionYellow = 25;

$heatDataRed = array();
$heatDataYellow = array();
foreach ($data as $key => $value) {
	$positionX = $value['x'];
	$positionY = $value['y'];
	$action = $value['action'];
	switch ($action) {
		case "move":

			$indexRedX = round($positionX / $precisionRed);
			$indexRedY = round($positionY / $precisionRed);
			$indexRed = $indexRedX . '_' . $indexRedY;
			if (isset($heatDataRed[$indexRed]) == true) {
				$precisionPointsRed = $heatDataRed[$indexRed]['points'] + 1;
				$heatDataRed[$indexRed]['x'] = $heatDataRed[$indexRed]['x'] + $positionX;
				$heatDataRed[$indexRed]['y'] = $heatDataRed[$indexRed]['y'] + $positionY;
			} else {
				$precisionPointsRed = 1;
				$heatDataRed[$indexRed]['x'] = $positionX;
				$heatDataRed[$indexRed]['y'] = $positionY;
			};
			$heatDataRed[$indexRed]['points'] = $precisionPointsRed;
			if ($precisionPointsRed > $maxPointsRed) {
				$maxPointsRed = $precisionPointsRed;
			};
			if ($precisionPointsRed < $minPointsRed) {
				$minPointsRed = $precisionPointsRed;
			};


			$indexYellowX = round($positionX / $precisionYellow);
			$indexYellowY = round($positionY / $precisionYellow);
			$indexYellow = $indexYellowX . '_' . $indexYellowY;

			if (isset($heatDataYellow[$indexYellow]) == true) {
				$precisionPointsYellow = $heatDataYellow[$indexYellow]['points'] + 1;
				$heatDataYellow[$indexYellow]['x'] = $heatDataYellow[$indexYellow]['x'] + $positionX;
				$heatDataYellow[$indexYellow]['y'] = $heatDataYellow[$indexYellow]['y'] + $positionY;
			} else {
				$precisionPointsYellow = 1;
				$heatDataYellow[$indexYellow]['x'] = $positionX;
				$heatDataYellow[$indexYellow]['y'] = $positionY;
			};
			$heatDataYellow[$indexYellow]['points'] = $precisionPointsYellow;
			if ($precisionPointsYellow > $maxPointsYellow) {
				$maxPointsYellow = $precisionPointsYellow;
			};
			if ($precisionPointsYellow < $minPointsYellow) {
				$minPointsYellow = $precisionPointsYellow;
			};

			break;
	};
};

$colorRed = '#FF0000';
$colorBlue = '#0000FF';
$colorYellow = '#FFFF00';
$colorGreen = '#008000';

$normalizeRedStart = 75;
$normalizeRedEnd = 250;
foreach ($heatDataRed as $key => $value) {
	$points = $value['points'];
	$positionX = $value['x'] / $points;
	$positionY = $value['y'] / $points;
	$radiusRed = normalize($points, $minPointsRed, $maxPointsRed, $normalizeRedStart, $normalizeRedEnd);
	$stepRed = round($radiusRed / 20);
	$imageTrack->draw_point_gradient($positionX, $positionY, $radiusRed, $colorBlue, 0x00, 0xFF, $stepRed);
};


$normalizeYellowStart = 5;
$normalizeYellowEnd = 100;
foreach ($heatDataYellow as $key => $value) {
	$points = $value['points'];
	$positionX = $value['x'] / $points;
	$positionY = $value['y'] / $points;
	$radiusYellow = normalize($points, $minPointsYellow, $maxPointsYellow, $normalizeYellowStart, $normalizeYellowEnd);
	$stepYellow = round($radiusYellow / 20);
	$imageTrack->draw_point_gradient($positionX, $positionY, $radiusYellow, $colorYellow, 0x00, 0xFF, $stepYellow);
};




//imagecolortransparent($imageTrack->image, imagecolorat($imageTrack->image, 0, 0));
//imagecopymerge($imageSite->image, $imageTrack->image, 0, 0, 0, 0, $imageWidth, $imageHeight, 50);
//imagecopy($imageSite->image, $imageTrack->image, 0, 0, 0, 0, $imageWidth, $imageHeight);
//$imageSite->output('png', 10);
$imageTrack->output('png', 10);
exit;
