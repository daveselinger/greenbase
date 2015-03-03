<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/3/15
 * Time: 12:28 AM
 */

include 'database_init.php';
$con = getDBConnection($db_config);

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