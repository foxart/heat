<?php

if (isset($_POST['id']) == false) {
	echo 'no id';
	exit;
} else {
	$id = $_POST['id'];
}

if (isset($_POST['action']) == false) {
	echo 'no action';
	exit;
} else {
	$action = $_POST['action'];
}


if (isset($_POST['data']) == false) {
	$data = false;
} else {
	$data = $_POST['data'];
}


$chatDirectory = PATH_CACHE . 'chat';
$chatFile = $chatDirectory . DS . 'chat_' . $id . EXT_LOG;

if (!is_dir($chatDirectory)) {
    mkdir($chatDirectory);
}

if (file_exists($chatFile) == false) {
	file_put_contents($chatFile, '');
}

$chatFileData = file($chatFile);

if ($action == 'refresh') {
	$messages = array();
	$currLine = $data;

//	if ($currLine === '0') {
//		$currLine = count($chatFileData) - 1;
//	}

	$endLine = count($chatFileData) - 1;

	if ($currLine <= $endLine) {
		while ($currLine <= $endLine) {
			$messages[] = $chatFileData[$currLine];
			$currLine++;
		}
		$message = json_encode($messages, true);
	}
	$result['line'] = count($chatFileData);
	$result['message'] = json_encode($messages, true);
	$result = json_encode($result);
	return $result;
}




if ($action == 'send') {
	$message = $data;
	if ($message !== false) {
		$fp = fopen($chatFile, 'a');
		fwrite($fp, $message);
		fwrite($fp, PHP_EOL);
		fclose($fp);
	}
}


// $sql_select =
// "
// SELECT
// sc.id
// FROM
// statistic_chat AS sc
// WHERE
// sc.fk_statistic = |ID|
// ";
// $faSql->load($sql_select);
// $faSql->set(array(
// 'ID' => $id,
// ));
// $faSql->run();
// $num_rows = $faSql->num_rows;
// if ($num_rows == 0)
// {
// $sql_insert =
// "
// INSERT INTO
// statistic_chat (fk_statistic)
// VALUES (|ID|)
// ";
// $faSql->load($sql_insert);
// $faSql->set(array(
// 'ID' => $id,
// ));
// $faSql->run();
// }
// $chatFile = PATH_FILES . 'chat_' . $id . EXT_LOG;
// if (file_exists($chatFile) == false) {
// file_put_contents($chatFile, '');
// }
// if ($message !== false) {
// $fp = fopen($chatFile, 'a');
// fwrite($fp, $message);
// fwrite($fp, PHP_EOL);
// fclose($fp);
// }
// $chatFileData = file($chatFile);
// if ($line != $chatFileData[count($chatFileData)-1]){
// $currLine = $line;
// $endLine = count($chatFileData)-1;
// while ($currLine <= $endLine) {
// $messages[] = $chatFileData[$currLine];
// $currLine++;
// }
// $messages = implode($messages, '<br/>');
// } else {
// $messages = $chatFileData[count($chatFileData)-1];
// }
