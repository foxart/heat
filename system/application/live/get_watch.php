<?php

$sql_select = "
		SELECT
			sa.id, sa.time, sa.action, sa.width, sa.height, sa.x, sa.y,
			statistic_url.id AS url_id, statistic_url.url
		FROM
			statistic_action AS sa
			
		LEFT JOIN
			statistic ON statistic.id = sa.fk_statistic
		LEFT JOIN
			statistic_geo ON statistic_geo.id = statistic.fk_statistic_geo
		LEFT JOIN
			statistic_url ON statistic_url.id = statistic.fk_statistic_url


		WHERE
			statistic_geo.id = |STATISTIC_ID|
		AND
			sa.id > |ACTION_ID|
		ORDER BY
			sa.time
		LIMIT 25
	";
$faSql->load($sql_select);
$faSql->set(array(
	'STATISTIC_ID' => $get_statistic,
	'ACTION_ID' => $get_action,
));
$faSql->run();
$faSql->num_rows;

// view($faSql);
// exit;

/* handle data */
if ($faSql->num_rows == 0) {
	$data = array();
} else {
	$data = $faSql->to_assoc();
	// $data = array_reverse($data);

	$maxWidth = 0;
	$maxHeight = 0;
	foreach ($data as $key => $value) {
		$width = $value['width'];
		$height = $value['height'];

		if ($width > $maxWidth) {
			$maxWidth = $width;
		};
		if ($height > $maxHeight) {
			$maxHeight = $height;
		};
	};

	// view($maxHeight);
	// view($maxWidth);
	// exit;


	$result = array();
	$nextX = 0;
	$nextY = 0;
	$nextWidth = 0;
	$prevX = 0;
	$prevY = 0;
	$prevWidth = 0;
	$count = 0;
	$length = count($data) - 1;
	foreach ($data as $key => $value) {
		//$data[$key]['url'] = rawurlencode($data[$key]['url']);
		$action = $value['action'];
		$time = $value['time'];
		$width = $value['width'];
		$height = $value['height'];
		// $maxWidth = $value['maxWidth'];
		// $maxHeight = $value['maxHeight'];
		$x = $value['x'];
		$y = $value['y'];
		if ($count < $length) {
			$nextAction = $data[$count + 1]['action'];
			if ($nextAction == 'move' or $nextAction == 'click' or $nextAction == 'scroll') {
				$nextX = $data[$count + 1]['x'];
				$nextY = $data[$count + 1]['y'];
				$nextWidth = $data[$count + 1]['width'];
			};
		};
		if ($action == 'move' or $action == 'click') {
			$positionX = ($maxWidth - $width) / 2 + $x;
			// $positionX = $x;
			$positionY = $y;
		} elseif ($action == 'enter') {
			$positionX = ($maxWidth - $nextWidth) / 2 + $nextX;
			// $positionX = $nextX;
			$positionY = $nextY;
		} elseif ($action == 'exit') {
			$positionX = ($maxWidth - $prevWidth) / 2 + $prevX;
			// $positionX = $prevX;
			$positionY = $prevY;
		} elseif ($action == 'scroll') {
			$positionX = $x;
			$positionY = $y;
		} else {
			$positionX = 0;
			$positionY = 0;
		};
		$data[$key]['x'] = $positionX;
		$data[$key]['y'] = $positionY;
		if ($action == 'move' or $action == 'click' or $action == 'scroll') {
			$prevX = $x;
			$prevY = $y;
			$prevWidth = $width;
		};
		$count++;
	};
};

 //view($data);
// exit;
/* handle data */
// return $data;
// $data = require_once 'data' . EXT_PHP;


if (count($data) > 0) {

	return json_encode($data);
} else {
	
};

exit;
// exit;