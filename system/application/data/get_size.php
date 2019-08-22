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
	$width = 1920;
} else {
	$width = $sqlResult['width'];
}

if ($sqlResult['height'] < 1) {
	$height = 1920;
} else {
	$height = $sqlResult['height'];
}

$data = array(
	'width' => $width,
	'height' => $height,
);

//view($data);

$result = json_encode($data, true);
//view($result);
//exit;
return $result;
