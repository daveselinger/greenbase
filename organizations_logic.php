<div class="row">
<?php

function writeOrg ($id, $orgName, $logoUrl, $description) {
  echo '<a href="./single_org.php?org=' . $id . '">';
  echo '<div id="listbox">';
  echo ' <div id="logobox"><img src="' . $logoUrl . '">';
  echo ' </div> ';
  echo ' <div id="textbox"> ';
  echo '<p>' . $orgName . '</p></div></div></a>';
}

include 'database_init.php';
include 'Organization.php';

$con = getDBConnection($db_config);

?>
</div>
