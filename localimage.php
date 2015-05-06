<?php
namespace greenbase;

include_once 'get_config.php';

function makeDirIfNeeded($path) {
  if (!file_exists($path)) {
    mkdir($path, 0777, true);
  }
}

if (!isset($_GET['org_id'])) {
	exit ('No org id');
}
//TODO: If the image fails to load, set logo_details valid = 0;

$org_id = $_GET['org_id'];
$width = 0;
$height = 0;
if (isset($_GET['width'])) {
  $width = intval($_GET['width']);
}
if (isset($_GET['height'])) {
  $height = intval($_GET['height']);
}
if ($width == 0 && $height == 0) {
  //use default value for width only
  $width = 100;
}
$logo_url = './remoteimages/originals/logo_' . $org_id . '.png';

if (!file_exists($logo_url)) {
  $logo_url = './img/no_logo.png';
  if (!file_exists($logo_url)) {
    echo (getcwd());
    exit ("Invalid file.");
  }
}

$handle = fopen($logo_url, 'rb');
$img = new \Imagick();
//exit ( "version" . $img->getVersion()['versionString']);
$img->readImageFile($handle);
if (!$img->trimImage(0.4)) {
  exit ("Trim failed");
}
$img->setImagePage(0, 0, 0, 0);
if ($width > 0 && $height > 0) {
  $img->thumbnailImage($width, $height, true);
} else {
  $img->thumbnailImage($width, $height, false);
}
header('Content-type: image/' . $img->getImageFormat());
echo $img;
?>