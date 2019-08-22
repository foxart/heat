<?php

if (isset($_POST['id']) == false) {
	echo 'no id';
	exit;
};

if (isset($_POST['data']) == false) {
	echo 'no data';
	exit;
};

$id = $_POST['id'];
$data = json_decode($_POST['data'], true);
// view($_POST['data']);


$sql_select = "
	SELECT
		s.id, s.max_width, s.max_height, s.enter, s.exit, s.click, s.move, s.move_distance, s.move_time, s.scroll, s.scroll_distance, s.scroll_time, s.spend_time
	FROM
		statistic AS s
	WHERE
		s.id = |ID|
";
$faSql->load($sql_select);
$faSql->set(array(
	'ID' => $id,
));
$faSql->run();
$num_rows = $faSql->num_rows;


// view($faSql->query);

if ($num_rows == 0) {
	echo 'no track id found';
	exit;
};



$statistic = $faSql->fetch_assoc();
$maxWidth = $statistic['max_width'];
$maxHeight = $statistic['max_height'];
$fk_statistic = $statistic['id'];

$statisticData = array();
$statisticData['exit'] = 0;
$statisticData['click'] = 0;
$statisticData['move'] = 0;
$statisticData['move_distance'] = 0;
$statisticData['move_time'] = 0;
$statisticData['scroll'] = 0;
$statisticData['scroll_distance'] = 0;
$statisticData['scroll_time'] = 0;
$statisticData['spend_time'] = 0;
$prevAction = null;
$prevTime = 0;
$prevX = 0;
$prevY = 0;

$sql_insert = array();

$http_user_agent = $_SERVER['HTTP_USER_AGENT'];

if (get_magic_quotes_gpc() == true) {
	$agent = $http_user_agent;
} else {
	$agent = mysql_real_escape_string($http_user_agent);
};
//$agent = $http_user_agent;
//$agent = 'agent';
// view($data);

foreach ($data as $data_item) {
	$currTime = $data_item['time'];
	$currAction = $data_item['action'];
	$width = $data_item['width'];
	$height = $data_item['height'];
	$currX = $data_item['x'];
	$currY = $data_item['y'];
	// $sql_insert[] =
	// "
	// INSERT INTO statistic_action (fk_statistic, time, agent, width, height, action, x, y)
	// VALUES ({$fk_statistic}, {$currTime}, '{$agent}', {$width}, {$height}, '{$currAction}', {$currX}, {$currY})
	// ";
	$sql_insert = "
		INSERT INTO statistic_action (fk_statistic, time, agent, width, height, action, x, y)
		VALUES (|FK_STATISTIC|, |TIME|, '|AGENT|', |WIDTH|, |HEIGHT|, '|ACTION|', |X|, |Y|)
	";

	$faSql->load($sql_insert);
	$faSql->set(array(
		'FK_STATISTIC' => $fk_statistic,
		'TIME' => $currTime,
		'AGENT' => $agent,
		'WIDTH' => $width,
		'HEIGHT' => $height,
		'ACTION' => $currAction,
		'X' => $currX,
		'y' => $currY,
	));
	$faSql->run();

//	echo ($faSql->query);


	if ($currAction == 'exit') {
		$statisticData['exit'] = $statisticData['exit'] + 1;
	};

	if ($currAction == 'click') {
		$statisticData['click'] = $statisticData['click'] + 1;
	};

	if ($currAction == 'move') {
		$statisticData['move'] = $statisticData['move'] + 1;
	};
	if ($currAction == 'scroll') {
		$statisticData['scroll'] = $statisticData['scroll'] + 1;
	};

	if ($currAction == 'move' and $prevAction == 'move') {
		$distance = get_distance($currX, $currY, $prevX, $prevY);
		$statisticData['move_distance'] = $statisticData['move_distance'] + $distance;
		$statisticData['move_time'] = $statisticData['move_time'] + ($currTime - $prevTime);
	};
	if ($currAction == 'scroll' and $prevAction == 'scroll') {
		$distance = get_distance($currX, $currY, $prevX, $prevY);
		$statisticData['scroll_distance'] = $statisticData['scroll_distance'] + $distance;
		$statisticData['scroll_time'] = $statisticData['scroll_time'] + ($currTime - $prevTime);
	};

	$statisticData['spend_time'] = $data[count($data) - 1]['time'] - $data[0]['time'];



	$prevAction = $currAction;
	$prevTime = $currTime;
	$prevX = $currX;
	$prevY = $currY;
};
// $sql_insert = implode(';', $sql_insert);
// $faSql->load($sql_insert);
// $faSql->run();

if ($width > $maxWidth) {
	$maxWidth = $width;
}

if ($height > $maxHeight) {
	$maxHeight = $height;
}


//echo $maxWidth;
//echo $maxHeight;
//exit;

$sql_update = "
	UPDATE
		statistic AS s
	SET
		s.max_width = |MAX_WIDTH|, s.max_height = |MAX_HEIGHT|,
		s.exit = |EXIT|, s.click = |CLICK|, s.move = |MOVE|, s.scroll = |SCROLL|,
		s.move_distance = |MOVE_DISTANCE|, s.move_time = |MOVE_TIME|,
		s.scroll_distance = |SCROLL_DISTANCE|, s.scroll_time = |SCROLL_TIME|,
		s.spend_time = |SPEND_TIME|
	WHERE
		id = |ID|
";
$faSql->load($sql_update);

$faSql->set(array(
	'ID' => $id,
	'MAX_WIDTH' => $maxWidth,
	'MAX_HEIGHT' => $maxHeight,
	'EXIT' => $statistic['exit'] + $statisticData['exit'],
	'CLICK' => $statistic['click'] + $statisticData['click'],
	'MOVE' => $statistic['move'] + $statisticData['move'],
	'MOVE_DISTANCE' => $statistic['move_distance'] + $statisticData['move_distance'],
	'MOVE_TIME' => $statistic['move_time'] + $statisticData['move_time'],
	'SCROLL' => $statistic['scroll'] + $statisticData['scroll'],
	'SCROLL_DISTANCE' => $statistic['scroll_distance'] + $statisticData['scroll_distance'],
	'SCROLL_TIME' => $statistic['scroll_time'] + $statisticData['scroll_time'],
	'SPEND_TIME' => $statistic['spend_time'] + $statisticData['spend_time'],
));

$faSql->run();

// print_r($data);
// print_r($statisticData);

return 'success';
