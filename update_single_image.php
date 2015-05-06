<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/3/15
 * Time: 12:28 AM
 */

include_once 'database_init.php';
$con = Database::getDefaultDBConnection();

function updateOrg($con, $id, $valid, $orientation, $stmt) {
  $stmt->bind_param("isi", $valid, $orientation, $id);
  if (!$stmt->execute() ){
    echo $con->error;
    exit ("Unable to update id=" . $id ." valid=". $valid ." orientation=".$orientation);
  }
}

$id = $_GET["logo"];
echo "Logo:" . $id;

$query = "SELECT id, logo_url, valid, orientation FROM logo_details_temp WHERE id=" . $id;
$results = $con->query($query);
if (is_null($results)) {
  echo $con->error;
  exit ("Unable to access logo_details_temp");
}
$logos = [];
$img = new Imagick();

while ($row = $results->fetch_assoc()) {
  flush();
  $logo = [];
  $logo["id"] = $row["id"];
  $logo["logo_url"] = $row["logo_url"];
  $logo["valid"] = $row["valid"];
  $logo["orientation"] = $row["orientation"];

  echo "<li>- Logo ID (" . $logo["id"] . "): ";
//Default value
  $width = 100;

  $handle = fopen($logo["logo_url"], 'rb');
  $loaded = false;
  echo "loading,";
  try {
    $loaded =$img->readImageFile($handle);
  }  catch (Exception $e) {
    echo("FAILED:" . $e->getMessage() . "<br>");
  }
  if ($loaded){
    echo "loaded, ";
    //Read image was a success
    $width = $img->getImageWidth();
    $height = $img->getImageHeight();
    $ratio = $width / $height;

    if ($ratio < .9) {
      $logo["orientation"] = "vertical";
    } else if ($ratio >  1.11) {
      $logo["orientation"] = "horizontal";
    } else {
      $logo["orientation"] = "square";
    }
    echo "orientation(" . $logo["orientation"] ."), ";
    $img->setImageFormat("png");
    $img->writeImage("./remoteimages/originals/logo_" . $logo["id"] . ".png");
    echo "stored.";
    $logo["valid"] = 1;
    $img->clear();
  } else {
    // Invalid image
    $logo["valid"] = 0;
  }

  $logos[] = $logo;
}
$results->free_result();
echo "</ol>\nEND<br>";

echo "Updating details\n<ol>";
$query = "UPDATE logo_details_temp SET valid = ?, orientation=? where id=?";
$stmt = $con->prepare($query);
foreach ($logos as $v) {
  flush();
  $id = $v["id"];
  echo "<li>Logo (" . $id .")";
  $valid = $v["valid"];
  $logo_url = $v["logo_url"];
  $orientation = $v["orientation"];
//  echo "LOGO ROW: $id = $valid, $logo_url.<br>\n";
  updateOrg($con, $id, $valid, $orientation, $stmt);
  echo " UPDATED";
}
$stmt->close();
echo "</ol>END\n<br>";

$con->close();
?>