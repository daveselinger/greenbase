<div class="row">
<?php
include_once 'Database.php';

function writeOrg ($id, $orgName, $logoUrl, $description) {
  echo '<a href="./single_org.php?org=' . $id . '">';
  echo '<div id="listbox">';
  echo ' <div id="logobox"><img src="' . $logoUrl . '">';
  echo ' </div> ';
  echo ' <div id="textbox"> ';
  echo '<p>' . $orgName . '</p></div></div></a>';
}

include 'Database.php';
include 'Organization.php';

$con = Database::getDefaultDBConnection();
?>
</div>
