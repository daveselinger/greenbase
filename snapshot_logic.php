<?php
include 'database_init.php';

// PHP code
function writeOrg($orgName, $logoUrl, $description, $size) {
  echo "<SPAN TITLE=\"" . $description . "\"><IMG ALT=\"" . $orgName . "\" WIDTH=" . $size . " HEIGHT=" . $size . " SRC='" . $logoUrl . "'></SPAN>";
}

$con = getDBConnection();
// Create a temporary table with the counts for each cell
$query = "CREATE TEMPORARY TABLE snapshot_counts AS SELECT org_type, focus, count(1) AS total FROM orgs GROUP BY org_type, focus";
if (!$con->query($query)) {
    exit ("Unable to make table");
}
if (!$con->query("ALTER TABLE snapshot_counts ADD INDEX (org_type, focus)")) {
  exit ("Unable to add index");
}
$focus_list = array();
$query = "SELECT focus FROM focus_list ORDER BY focus_order, focus";
$results = $con->query($query);
while ($row = $results->fetch_assoc()) {
  $focus_list[] = $row["focus"];
}
$results->free_result();
echo count($focus_list) . " focus areas<br>";
$org_type_list = array();
$query = "SELECT org_type FROM org_type_list ORDER BY org_type_order, org_type";
$results = $con->query($query);
while ($row = $results->fetch_assoc()) {
  $org_type_list[] = $row["org_type"];
}
$results->free_result();
echo count($org_type_list) . " org types<br>";
echo '<hr>';
$query = "CREATE TEMPORARY TABLE snapshot_data AS SELECT orgs.*, snapshot_counts.total FROM orgs LEFT JOIN snapshot_counts on (orgs.org_type = snapshot_counts.org_type AND orgs.focus = snapshot_counts.focus) ORDER BY orgs.org_type ASC, orgs.focus ASC";
if (!$con->query($query)) {
    exit ("Unable to make table");
}
if (!$con->query("ALTER TABLE snapshot_data ADD INDEX (org_type, focus)")) {
  exit ("Unable to add index");
}
$query = "SELECT name, logo_url, description, total FROM snapshot_data WHERE org_type = ? AND focus = ?";
$stmt = $con->prepare($query);
echo '<table>';
foreach($org_type_list as $org_type) {
  echo '<tr><td>' . $org_type . '</td>';
  foreach($focus_list as $focus) {
    echo '<td>';
    $stmt->bind_param("ss", $org_type, $focus);
    $count = 0;
    $stmt->execute();
    $stmt->bind_result($name, $logo_url, $description, $total);
    while ($stmt->fetch()) {
      $count++;
      $size = 100;
      $breakpoint = 1;
      if ($total >= 9) {
        $size = 50;
        $breakpoint = 3;
      } elseif ($total >= 4) {
        $size = 60;
        $breakpoint = 2;
      } elseif ($total > 2) {
        $size = 100;
        $breakpoint = 2;
      }
      writeOrg($name, $logo_url, $description, $size);
      if ($count == $breakpoint) {
        echo '<br>';
        $count = 0;
      }
    }
    echo '</td>';
  }
  echo '</tr>';
}
echo '<tr><td></td>';
foreach($focus_list as $focus) {
  echo '<td>' . $focus . '</td>';
}
echo '</tr></table>'; 
$con->close();
?>
