<?php
include 'database_init.php';
$con = getDBConnection($db_config);

// PHP code
function updateOrg($con, $id, $valid, $orientation) {
  $query = "UPDATE logo_details_temp SET valid = ?, orientation=? where id=?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("isi", $valid, $orientation, $id);
  if (!$stmt->execute() ){
    exit ("Unable to update id=" . $id ." valid=". $valid ." orientation=".$orientation);
  }
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
$query = "CREATE TABLE logo_details_temp AS SELECT id, logo_url, 0 as valid, 'square' as orientation FROM orgs";
if (!$con->query($query)) {
  exit ("Unable to make table logo_details_temp");
}
$query = "ALTER TABLE logo_details_temp ADD INDEX (id)";
if (!$con->query($query)) {
  exit ("Temp table index add failed.");
}

//TODO: Download each logo sequentially and update it!

$query = "RENAME TABLE logo_details to logo_details_old, logo_details_temp to logo_details";
if (!$con->query($query)) {
  exit ("FATAL ERROR Unable to execute table swaps during image update". $con->error);
}

echo 'complete--success';
$con->close();
?>