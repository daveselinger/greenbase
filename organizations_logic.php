<?php
namespace greenbase;
?>
<div class="row">
<?php
include_once 'Database.php';
include_once 'Organization.php';

function writeOrg ($id, $orgName, $logoUrl, $description) {
  echo '<a href="./single_org.php?org_id=' . $id . '">';
  echo '<div id="listbox">';
  echo ' <div id="logobox"><img src="' . $logoUrl . '">';
  echo ' </div> ';
  echo ' <div id="textbox"> ';
  echo '<p>' . $orgName . '</p></div></div></a>';
}

$con = Database::getDefaultDBConnection();

$orgs = Organization::getOrgs($con, 1, 1);

?>
</div>
