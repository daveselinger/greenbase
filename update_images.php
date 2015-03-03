<?php
include 'database_init.php';
$con = getDBConnection($db_config);

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

echo "Dropping old logo_details_temp and logo_details_old tables<br>\n";
// Init and drop old tables if they're hanging around.
$query = "DROP TABLE IF EXISTS logo_details_temp, logo_details_old";
if (!$con->query($query)) {
  echo $con->error;
  exit ("Basic table dropping initialization failed.");
}
echo "done<br><br>\n\n";

echo "Creating logo_details table if needed<br>\n";
// Init and drop old tables if they're hanging around.
$query = "CREATE TABLE IF NOT EXISTS logo_details (ID int)";
if (!$con->query($query)) {
  echo $con->error;
  exit ("error creating skeleton table if needed.");
}
echo "done<br><br>\n\n";

echo "Populating logo_details_temp and creating it<br>\n";
// Create a table with the image details -- named temp so we do the swap one time only.
$query = "CREATE TABLE logo_details_temp AS SELECT id, logo_url, 0 as valid, 'horizontal123123123123' as orientation FROM orgs WHERE org_status = 1";
if (!$con->query($query)) {
  echo $con->error;
  exit ("Unable to make table logo_details_temp");
}
echo "done<br><br>\n\n";

echo "Creating index on logo_details_temp";
$query = "ALTER TABLE logo_details_temp ADD INDEX (id)";
if (!$con->query($query)) {
  echo $con->error;
  exit ("Temp table index add failed.");
}
echo "done<br><br>\n\n";

echo "BEGIN Iterating logos\n<ol>";
$query = "SELECT id, logo_url, valid, orientation FROM logo_details_temp";
$results = $con->query($query);
if (is_null($results)) {
  echo $con->error;
  exit ("Unable to access logo_details_temp");
}
initFilesystem();

while ($row = $results->fetch_assoc()) {
  flush();
  $logo = [];
  $logo["id"] = $row["id"];
  $logo["logo_url"] = $row["logo_url"];
  $logo["valid"] = $row["valid"];
  $logo["orientation"] = $row["orientation"];

  echo "". $logo["id"] . ": <iframe src='./update_single_image.php?logo=" . $logo["id"] . "'></iframe><br><br>";
//Default value
}
$results->free_result();
echo "\nEND<br>";

echo 'complete--success';
$con->close();
?>
When all images are updated, click <a href="./update_images_complete.php">here</a>.n