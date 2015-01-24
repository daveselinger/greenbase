<?php

function writeOrg ($id, $orgName, $logoUrl, $description) {
  echo '<a href="' . substr($orgName, 0 ,1) . '">';
  echo '<div id="listbox">';
  echo ' <img src="' . $logoUrl . '">';
  echo '<p>' . $orgName . '</p></div></a>';
}

include 'database_init.php';

$con = getDBConnection();
$query = "SELECT id, name, logo_url, description FROM orgs ORDER BY name";
$results = $con->query($query);
while ($row = $results->fetch_assoc()) {
  $id = $row["id"];
  $orgName = $row["name"];
  $logoUrl = $row["logo_url"];
  $description = $row["description"];
  writeOrg($id, $orgName, $logoUrl, $description);
}
?>

