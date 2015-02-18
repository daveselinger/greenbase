<?php
include 'database_init.php';
$con = getDBConnection($db_config);

function updateOrg($con, $id, $valid, $orientation, $stmt) {
  $stmt->bind_param("isi", $valid, $orientation, $id);
  if (!$stmt->execute() ){
    exit ("Unable to update id=" . $id ." valid=". $valid ." orientation=".$orientation);
  }
}

/**
 *
 * Used to set up all of the filesystem directory tree
 */
function makeDirIfNeeded($path) {
  if (!file_exists($path)) {
    mkdir($path, 0777, true);
  }
}

function initFilesystem() {
  // First see if the file is available locally. If not, then download and revise.
  makeDirIfNeeded('./remoteimages/');
  makeDirIfNeeded('./remoteimages/originals/');
  makeDirIfNeeded('./remoteimages/snapshot/');
}

// Init and drop old tables if they're hanging around.
$query = "DROP TABLE IF EXISTS logo_details_temp, logo_details_old";
if (!$con->query($query)) {
  exit ("Basic table dropping initialization failed.");
}

// Init and drop old tables if they're hanging around.
$query = "CREATE TABLE IF NOT EXISTS logo_details (ID int)";
if (!$con->query($query)) {
  exit ("error creating skeleton table if needed.");
}

// Create a table with the image details -- named temp so we do the swap one time only.
$query = "CREATE TABLE logo_details_temp AS SELECT id, logo_url, 0 as valid, 'square' as orientation FROM orgs WHERE org_status = 1";
if (!$con->query($query)) {
  exit ("Unable to make table logo_details_temp");
}
$query = "ALTER TABLE logo_details_temp ADD INDEX (id)";
if (!$con->query($query)) {
  exit ("Temp table index add failed.");
}

$query = "SELECT id, logo_url, valid, orientation FROM logo_details_temp";
$results = $con->query($query);
if (is_null($results)) {
  exit ("Unable to access logo_details_temp");
}
$logos = [];
$img = new Imagick();
while ($row = $results->fetch_assoc()) {
  $logo = [];
  $logo["id"] = $row["id"];
  $logo["logo_url"] = $row["logo_url"];
  $logo["valid"] = $row["valid"];
  $logo["orientation"] = $row["orientation"];

  initFilesystem();

//Default value
  $width = 100;

  $handle = fopen($logo["logo_url"], 'rb');
  $loaded = false;
  try {
    $loaded =$img->readImageFile($handle);
  }  catch (Exception $e) {
    echo("Exception on id(" . $logo["id"] . "):" . $e->getMessage() . "<br>");
  }
  if ($loaded){
    //Read image was a success
    $width = $img->getImageWidth();
    $height = $img->getImageHeight();
    $ratio = $width / $height;

    if ($ratio < .85) {
      $logo["orientation"] = "vertical";
    } else if ($ratio >  1.17) {
      $logo["orientation"] = "horizontal";
    } else {
      $logo["orientation"] = "square";
    }
    $img->setImageFormat("png");
    $img->writeImage("./remoteimages/originals/logo_" . $logo["id"] . ".png");
    $logo["valid"] = 1;
    $img->clear();
  } else {
    // Invalid image
    $logo["valid"] = 0;
  }

  $logos[] = $logo;
}
$results->free_result();

$query = "UPDATE logo_details_temp SET valid = ?, orientation=? where id=?";
$stmt = $con->prepare($query);
foreach ($logos as $v) {
  $id = $v["id"];
  $valid = $v["valid"];
  $logo_url = $v["logo_url"];
  $orientation = $v["orientation"];
//  echo "LOGO ROW: $id = $valid, $logo_url.<br>\n";
  updateOrg($con, $id, $valid, $orientation, $stmt);
}
$stmt->close();

$query = "RENAME TABLE logo_details to logo_details_old, logo_details_temp to logo_details";
if (!$con->query($query)) {
  exit ("FATAL ERROR Unable to execute table swaps during image update". $con->error);
}

echo 'complete--success';
$con->close();
?>