<?php

// $sizeErrorX = 1920;
// $sizeErrorY = 1080;
// $resizeThumbnailY = 64;
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
// $imageName = $result['image'];
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
// exit;
// if (file_exists($imagePath) == true)
// {
// $error = false;
// if (count($data) > 0)
// {
// $error = false;
// } else {
// $error = true;
// $errorText = "no data";
// };
// } else {
// $error = true;
// $errorText = "file not found: " . $imageName;
// };


/* handle data */
// if ($error == true)
// {
// $imageScreen = new faImage();
// $imageScreen->create($sizeErrorX, $sizeErrorY);
// $color_red = imagecolorallocate($imageScreen->image, 244, 78, 98);
// $color_white = imagecolorallocate($imageScreen->image, 255, 255, 255);
// imagefill($imageScreen->image, 0, 0, $color_white);
// $imageScreen->draw_text('center', 'center', $errorText, 5, $color_red);
// } else {
// $imageScreen = new faImage();
// $imageScreen->load($imagePath);
// };
// $width = $imageScreen->get_width();
// $height = $imageScreen->get_height();

$imageTrack = new faImage();
$imageTrack->create($imageWidth, $imageHeight);



// imagecopy($imageTrack->image, $imageScreen->image, 0, 0, 0, 0, $width, $height);
$alpha = 96; //0-127
$colorLine = imagecolorallocatealpha($imageTrack->image, 0, 130, 200, $alpha);

$colorEnter = imagecolorallocatealpha($imageTrack->image, 18, 173, 42, $alpha);
$colorExit = imagecolorallocatealpha($imageTrack->image, 244, 78, 98, $alpha);

$colorFirst = imagecolorallocate($imageTrack->image, 18, 173, 42);
$colorLast = imagecolorallocate($imageTrack->image, 244, 78, 98);

$colorClick = imagecolorallocatealpha($imageTrack->image, 255, 217, 51, $alpha);
$colorScroll = imagecolorallocatealpha($imageTrack->image, 0, 0, 0, $alpha);

// $color_red = imagecolorallocate($imageTrack->image, 244, 78, 98);
// $color_green = imagecolorallocate($imageTrack->image, 18, 173, 42);
// $color_blue = imagecolorallocate($imageTrack->image, 0, 130, 200);
// $color_magenta = imagecolorallocate($imageTrack->image, 237, 0, 140);

$positionPrevX = 0;
$positionPrevY = 0;
$count = 0;
$length = count($data) - 1;

foreach ($data as $key => $value) {
	$width = $value['width'];
	$height = $value['height'];
	// $maxWidth = $value['maxWidth'];
	// $maxHeight = $value['maxHeight'];
	// $positionX = ($maxWidth - $width)/2 + $value['x'];
	$positionX = $value['x'];
	$positionY = $value['y'];
	$action = $value['action'];

	if ($count == 0) { //first record
		$positionPrevX = $positionX;
		$positionPrevY = $positionY;
		$imageTrack->draw_point($positionX, $positionY, 32, $colorFirst);
	};
	if ($count == $length) { //last record
		$imageTrack->draw_point($positionX, $positionY, 32, $colorLast);
	};

	switch ($action) {
		case "enter":
			$imageTrack->draw_point($positionX, $positionY, 24, $colorEnter);
			break;
		case "exit":
			$imageTrack->draw_point($positionX, $positionY, 24, $colorExit);
			break;
		case "click":
			$imageTrack->draw_point($positionX, $positionY, 16, $colorClick);
			break;
		case "move":
			$imageTrack->draw_point($positionX, $positionY, 8, $colorLine);
			$imageTrack->draw_line($positionPrevX, $positionPrevY, $positionX, $positionY, $colorLine, 2);
			$positionPrevX = $positionX;
			$positionPrevY = $positionY;
			break;
		case "scroll":
			$imageTrack->draw_point($positionPrevX, $positionPrevY, 16, $colorScroll);
			// $imageTrack->draw_point($positionX, $positionY, 16, $colorScroll);
			break;
	};

	$count++;
};

if (isset($_GET['height']) == true) {
	$resizeY = $_GET['height'];
	$imageTrack->resize_to_height($resizeY);
};

// $imagePathThumbnail = PATH_LOG . 'thumbnail.' . $imageName . '.jpg';
// if (file_exists($imagePathThumbnail) == false)
// {
// $imageScreen->resize_to_height($resizeThumbnailY);
// $imageScreen->save($image_path_jpg, 'jpg', 60);
// };

$imageTrack->output('png', 10);
exit;
