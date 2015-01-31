<div class="row">
<?php

function writeOrg ($id, $orgName, $logoUrl, $description) {
  echo '<a href="' . substr($orgName, 0 ,1) . '">';
  echo '<div id="listbox">';
  echo ' <div id="logobox"><img src="' . $logoUrl . '">';
  echo ' </div> ';
  echo ' <div id="textbox"> ';
  echo '<p>' . $orgName . '</p></div></div></a>';
}

include 'database_init.php';

$con = getDBConnection($db_config);
$query = "SELECT id, name, logo_url, description FROM orgs ORDER BY name";
$results = $con->query($query);
$i=0;
while ($row = $results->fetch_assoc()) {
  $id = $row["id"];
  $orgName = $row["name"];
  $logoUrl = $row["logo_url"];
  $logoUrl = './remoteimage.php?org=' . $id;
  $description = $row["description"];
  writeOrg($id, $orgName, $logoUrl, $description);
  $i++;
  if ($i % 4 == 0 ) {
    echo '</div>';
    echo '<div class="row">';
  }
}
?>
</div>
