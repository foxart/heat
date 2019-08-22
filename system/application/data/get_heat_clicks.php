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

//view($data);
//exit;


$imageTrack = new faImage();
$imageTrack->create($imageWidth, $imageHeight);

$maxPointsRed = 0;
$minPointsRed = 0;
$maxPointsYellow = 0;
$minPointsYellow = 0;

$precisionRed = 15;
$precisionYellow = 10;

$heatDataRed = array();
$heatDataYellow = array();
foreach ($data as $key => $value) {
	$positionX = $value['x'];
	$positionY = $value['y'];
	$action = $value['action'];
	switch ($action) {
		case "click":

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
$colorYellow = '#FFFF00';
$colorGreen = '#008000';

$normalizeRedStart = 5;
$normalizeRedEnd = 150;
foreach ($heatDataRed as $key => $value) {
	$points = $value['points'];
	$positionX = $value['x'] / $points;
	$positionY = $value['y'] / $points;
	$radiusRed = normalize($points, $minPointsRed, $maxPointsRed, $normalizeRedStart, $normalizeRedEnd);
	$stepRed = round($radiusRed / 20);
	$imageTrack->draw_point_gradient($positionX, $positionY, $radiusRed, $colorRed, 0x00, 0xFF, $stepRed);
};


$normalizeYellowStart = 3;
$normalizeYellowEnd = 75;
foreach ($heatDataYellow as $key => $value) {
	$points = $value['points'];
	$positionX = $value['x'] / $points;
	$positionY = $value['y'] / $points;
	$radiusYellow = normalize($points, $minPointsYellow, $maxPointsYellow, $normalizeYellowStart, $normalizeYellowEnd);
	$stepYellow = round($radiusYellow / 20);
	$imageTrack->draw_point_gradient($positionX, $positionY, $radiusYellow, $colorYellow, 0x00, 0xFF, $stepYellow);
};


$imageTrack->output('png', 50);
exit;
