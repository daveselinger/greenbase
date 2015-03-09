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

$restart = isset($_GET["restart"]);

if ($restart) {
  // ONLY DROP THE TEMP TABLE IF DOING FROM SCRATCH
  echo "Dropping old logo_details_temp tables<br>\n";
  // Init and drop old tables if they're hanging around.
  $query = "DROP TABLE IF EXISTS logo_details_temp";
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

  echo "Creating logo_details_temp and populating it from scratch<br>\n";
  // Create a table with the image details -- named temp so we do the swap one time only.
  $query = "CREATE TABLE logo_details_temp AS SELECT id, logo_url, 0 AS valid, 'horizontal123123123123' AS orientation FROM orgs WHERE org_status = 1";
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
} else {
  echo "Dropping old logo_details_temp<br>\n";
  // Init and drop old tables if they're hanging around.
  $query = "DROP TABLE IF EXISTS logo_details_temp";
  if (!$con->query($query)) {
    echo $con->error;
    exit ("Basic table dropping initialization failed.");
  }
  echo "done<br><br>\n\n";

  // True update case
  echo "Creating logo_details_temp and populating it from current logo_details<br>\n";
  // Create a table with the image details -- named temp so we do the swap one time only.
  $query = "CREATE TABLE logo_details_temp AS SELECT * FROM logo_details WHERE valid = 1";
  if (!$con->query($query)) {
    echo $con->error;
    exit ("Unable to make table logo_details_temp");
  }

  echo "Adding in the logos for formerly invalid files, newly added organizations. These will be the files added/downloaded.";
  $query = "INSERT INTO logo_details_temp (id, logo_url, valid, orientation) ".
    "SELECT orgs.id, orgs.logo_url, 0 AS valid, 'horizontal123123123123' AS orientation " .
    "FROM orgs LEFT JOIN logo_details_temp ON orgs.id = logo_details_temp.id WHERE logo_details_temp.id IS NULL;";
  if (!$con->query($query)) {
    echo $con->error;
    exit ("Unable to complete populating table logo_details_temp with the logos to update");
  }

  echo "done<br><br>\n\n";
}

//Doesn't need to be specific as in restart all valid will=0 no matter what.
echo "BEGIN Iterating logos\n<ol>";
$query = "SELECT id, logo_url, valid, orientation FROM logo_details_temp WHERE valid = 0";

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