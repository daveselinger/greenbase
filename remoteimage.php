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

$con = new mysqli("mysql.climatebase.dreamhosters.com", "climatebase", "climatebas3");
if ($con->connect_error) {
	exit ('Connect error (' .mysqli_connect_errno() .') '.mysqli_connect_error());
} else {
}
$con->select_db("climatebase");

// First see if the file is available locally. If not, then download and revise.
makeDirIfNeeded('./remoteimages/');
makeDirIfNeeded('./remoteimages/originals/');
makeDirIfNeeded('./remoteimages/snapshot/');

$handle = fopen('http://upload.wikimedia.org/wikipedia/commons/thumb/7/77/The_Weather_Channel_logo_2005-present.svg/500px-The_Weather_Channel_logo_2005-present.svg.png', 'rb');
$img = new Imagick();
$img->readImageFile($handle);
$img->thumbnailImage(100, 0);
header('Content-type: image/png');
echo $img;
?>
