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
$img->readImageFile($handle);
$img->thumbnailImage(100, 0);
header('Content-type: image/png');
echo $img;
?>
