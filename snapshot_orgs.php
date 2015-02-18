<?php
header('Content-Type: application/json');
?>
{
<?php
include 'database_init.php';

// PHP code
function writeOrg($orgName, $id, $description, $orientation) {
  echo("\"$id\": { \"id\": \"$id\", \"name\": \"$orgName\", \"logo\": \"./localimage.php?org=$id\", \"description\": \"$description\", \"orientation\": \"$orientation\"}");
}

$con = getDBConnection($db_config);

$query = "SELECT orgs.id, name, headline, description, org_type, focus, logo_details.orientation FROM orgs LEFT JOIN logo_details ON (orgs.id = logo_details.id) WHERE orgs.org_status = 1 AND logo_details.valid = 1";
$results = $con->query($query);
if (is_null($results) || false == $results) {
  exit ("Unable to access orgs:" . $con->error);
}

$first = true;
while($row = $results->fetch_assoc()) {
  if (!$first) {
    echo ",\n";
  }
  writeOrg($row["name"], $row["id"], $row["headline"], $row["orientation"]);
  $first = false;
}
$results->free_result();
$con->close();
?>

}
