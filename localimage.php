<?php
function makeDirIfNeeded($path) {
  if (!file_exists($path)) {
    mkdir($path, 0777, true);
  }
}

if (!isset($_GET['org'])) {
	exit ('No org id');
}

$org_id = $_GET['org'];
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
  exit ("ERROR IMAGES NOT INITIALIZED: " + $logo_url);
}

$handle = fopen($logo_url, 'rb');
$img = new Imagick();
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