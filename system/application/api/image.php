<?php

if (isset($_POST['id']) == false) {
	echo 'no id';
	exit;
}

if (isset($_POST['image']) == false) {
	echo 'no image';
	exit;
}

$statistic_id = $_POST['id'];


// $image = $record['image'];
// if (isset($image) == false)
// {
// $imageName = get_unique();
// } else {
// $imageName = $image;
// };

$post_image = base64_decode($_POST['image']);
$image_path_jpg = PATH_CACHE . $statistic_id . '.jpg';
$imageResource = imagecreatefromstring($post_image);
$imageWidth = imagesx($imageResource);
$imageHeight = imagesy($imageResource);
$imageScreen = new faImage();
$imageScreen->create($imageWidth, $imageHeight);
imagecopy($imageScreen->image, $imageResource, 0, 0, 0, 0, $imageWidth, $imageHeight);
$imageScreen->save($image_path_jpg, 'jpg', 40);

return $image_path_jpg;

// $sql_update =
// "
// UPDATE
// statistic_url
// SET
// image = '|IMAGE|', width = '|WIDTH|', height = '|HEIGHT|'
// WHERE
// id = '|ID|'
// ";
// $faSql->load($sql_update);
// $faSql->set(array(
// 'ID' => $statistic_url_id,
// 'IMAGE' => $imageName,
// 'WIDTH' => $imageWidth,
// 'HEIGHT' => $imageHeight,
// ));
// $faSql->run();

//return 'image';
