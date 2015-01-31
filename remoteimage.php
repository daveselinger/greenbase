<?php
include 'database_init.php';

function makeDirIfNeeded($path) {
  if (!file_exists($path)) {
    mkdir($path, 0777, true);
  }
}

if (!isset($_GET['org'])) {
	exit ('No org id');
}

$org_id = $_GET['org'];
$width = 100;
//Default value
if (isset($_GET['width'])) {
  $width = intval($_GET['width']);
}
if (!isset($width) || $width <= 0) {
  $width = 100;
  exit("INVALID WIDTH");
  //Default value just in case
}

$con = getDBConnection($db_config);

// First see if the file is available locally. If not, then download and revise.
makeDirIfNeeded('./remoteimages/');
makeDirIfNeeded('./remoteimages/originals/');
makeDirIfNeeded('./remoteimages/snapshot/');

$query = "SELECT logo_url FROM orgs WHERE id=?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $org_id);
$stmt->execute();
$stmt->bind_result($logo_url);

if (!$stmt->fetch()) {
  exit ("Invalid org");
}
if (!isset($logo_url)) {
  exit ("Invalid url");
}

$handle = fopen($logo_url, 'rb');
$img = new Imagick();
//exit ( "version" . $img->getVersion()['versionString']);
$img->readImageFile($handle);
if (!$img->trimImage(0.4)) {
  exit ("Trim failed");
}
$img->setImagePage(0, 0, 0, 0);
$img->thumbnailImage($width, 0);
header('Content-type: image/' . $img->getImageFormat());
echo $img;
$con->close();
?>
