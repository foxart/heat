<?php

$sql_select = "
		SELECT
			MAX(max_width) AS width, MAX(max_height) AS height
		FROM
			statistic AS s
		WHERE
			IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
		AND
			IF(|USER_ID| = 0, 1, s.fk_statistic_geo = |USER_ID|)
	";
$faSql->load($sql_select);
$faSql->set(array(
	'LINK_ID' => $get_link,
	'USER_ID' => $get_user,
));
$faSql->run();

$sqlResult = $faSql->fetch_assoc();


if ($sqlResult['width'] < 1) {
	$maxWidth = 1920;
} else {
	$maxWidth = $sqlResult['width'];
}

if ($sqlResult['height'] < 1) {
	$maxHeight = 1920;
} else {
	$maxHeight = $sqlResult['height'];
}


//$sql_count = "
//		SELECT COUNT(s.id) AS count
//		FROM
//			statistic AS s
//		LEFT JOIN
//			statistic_action AS sa ON sa.fk_statistic = s.id
//		WHERE
//			IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
//		AND
//			IF(|USER_ID| = 0, 1, s.fk_statistic_geo = |USER_ID|)
//		-- AND
//		--	DATE_FORMAT(s.time, '%Y') = DATE_FORMAT(NOW(), '%Y')
//		AND
//			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') >= '|DATE_START|')
//		AND
//			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '%Y-%m-%d') <= '|DATE_END|')
//		AND
//			IF('|ACTION|' = 0, 1, sa.action = '|ACTION|')
//	";
//$faSql->load($sql_count);
//$faSql->set(array(
//	'LINK_ID' => $get_link,
//	'USER_ID' => $get_user,
//	'DATE_START' => $get_date_start,
//	'DATE_END' => $get_date_end,
//	'ACTION' => $get_action,
//));
//
//$faSql->run();
//$data = $faSql->fetch_assoc();

$limit = 100;

//if ($data['count'] > $limit) {
//	$format = '%Y-%m';
//} else {
//	$format = '%Y-%m-%d';
//}

$format = '%Y-%m-%d';


if (isset($_GET['id']) == true) {
	$get_id = $_GET['id'];
} else {
	$get_id = 1;
}


$sql_select = "
		SELECT
			sa.id, sa.time, sa.action, sa.width, sa.height, sa.x, sa.y
		FROM
			statistic AS s
		LEFT JOIN
			statistic_action AS sa ON sa.fk_statistic = s.id
		WHERE
			IF(|ID| = 0, 1, sa.id > |ID|)
		AND
			IF(|LINK_ID| = 0, 1, s.fk_statistic_url = |LINK_ID|)
		AND
			IF(|USER_ID| = 0, 1, s.fk_statistic_geo = |USER_ID|)
		-- AND
		--	DATE_FORMAT(s.time, '|FORMAT|') = DATE_FORMAT(NOW(), '|FORMAT|')
		AND
			IF('|DATE_START|' = 0, 1, DATE_FORMAT(s.time, '|FORMAT|') >= '|DATE_START|')
		AND
			IF('|DATE_END|' = 0, 1, DATE_FORMAT(s.time, '|FORMAT|') <= '|DATE_END|')
		AND
			IF('|ACTION|' = '', 1, sa.action = '|ACTION|')
		ORDER BY
			sa.time
		LIMIT |LIMIT|
	";
$faSql->load($sql_select);
$faSql->set(array(
	'ID' => $get_id,
	'LINK_ID' => $get_link,
	'USER_ID' => $get_user,
	'FORMAT' => $format,
	'DATE_START' => $get_date_start,
	'DATE_END' => $get_date_end,
	'LIMIT' => $limit,
	'ACTION' => $get_action,
));
$faSql->run();
//view($faSql);
//exit;
/* handle data */
if ($faSql->num_rows == 0) {
	$data = array();
} else {
	$data = $faSql->to_assoc();

//	$maxWidth = 0;
//	$maxHeight = 0;
//	foreach ($data as $key => $value) {
//		$width = $value['width'];
//		$height = $value['height'];
//
//		if ($width > $maxWidth) {
//			$maxWidth = $width;
//		}
//		if ($height > $maxHeight) {
//			$maxHeight = $height;
//		}
//	}


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
		$action = $value['action'];
		$time = $value['time'];
		$width = $value['width'];
		$height = $value['height'];
//		$maxWidth = $value['maxWidth'];
//		$maxHeight = $value['maxHeight'];
		$x = $value['x'];
		$y = $value['y'];
		if ($count < $length) {
			$nextAction = $data[$count + 1]['action'];
			if ($nextAction == 'move' or $nextAction == 'click' or $nextAction == 'scroll') {
				$nextX = $data[$count + 1]['x'];
				$nextY = $data[$count + 1]['y'];
				$nextWidth = $data[$count + 1]['width'];
			}
		}
		if ($action == 'move' or $action == 'click') {
			$positionX = ($maxWidth - $width) / 2 + $x;
//			$positionX = $x;
			$positionY = $y;
		} elseif ($action == 'enter') {
			$positionX = ($maxWidth - $nextWidth) / 2 + $nextX;
//			$positionX = $nextX;
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
		}
		$data[$key]['x'] = $positionX;
		$data[$key]['y'] = $positionY;
		if ($action == 'move' or $action == 'click' or $action == 'scroll') {
			$prevX = $x;
			$prevY = $y;
			$prevWidth = $width;
		}
		$count ++;
	}
}
/* handle data */
// return $data;
// $data = require_once 'data' . EXT_PHP;
$result = json_encode($data, JSON_PRETTY_PRINT);

return $result;

//view($result);
