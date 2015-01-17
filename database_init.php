<?php
function getDBConnection() {
$con = new mysqli("mysql.climatebase.dreamhosters.com", "climatebase", "climatebas3");
if ($con->connect_error) {
	exit ('Connect error (' .mysqli_connect_errno() .') '.mysqli_connect_error());
} else {
}
$con->select_db("climatebase");
return $con;
}

?>
