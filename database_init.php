<?php
try {
	include 'config.php';
} catch (Exception $e) {
	exit ('Error reading "config.php". Database configuration required here: db_username and db_password:' . $e->getMessage());
}

if (!isset($db_url) || !isset($db_username) || !isset($db_password) || !isset($db_name) || ($db_url == '')
	|| ($db_username == '') || ($db_password == '')  || ($db_name == '') ) {
	exit( 'No database configuration in "config.php"');
}

function getDBConnection() {
$con = new mysqli($db_url, $db_username, $db_password, $db_name);
if ($con->connect_error) {
	exit ('Connect error (' .mysqli_connect_errno() .') '.mysqli_connect_error());
} else {
}
$con->select_db("climatebase");
return $con;
}

$con = getDBConnection();
$results = $con->query("Select count(1) from orgs");
if ($results->fetch_array()) {
  echo $results[0] . "rows";
}
$results->close();
$con->close();
?>
