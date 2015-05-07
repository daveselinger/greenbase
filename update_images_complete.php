<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/3/15
 * Time: 12:28 AM
 */
namespace greenbase;

include_once 'Database.php';
$con = Database::getDefaultDBConnection();

echo "Dropping old logo_details_old tables<br>\n";
// Init and drop old tables if they're hanging around.
$query = "DROP TABLE IF EXISTS logo_details_old";
if (!$con->query($query)) {
  echo $con->error;
  exit ("Basic table dropping initialization failed.");
}
echo "done<br><br>\n\n";

echo "Executing table swap from logo_details to logo_details_old, and from temp to logo_details\n<br>";
$query = "RENAME TABLE logo_details to logo_details_old, logo_details_temp to logo_details";
if (!$con->query($query)) {
  echo $con->error;
  exit ("FATAL ERROR Unable to execute table swaps during image update". $con->error);
}
echo "done<br><br>\n\n";

echo 'complete--success';
$con->close();
?>