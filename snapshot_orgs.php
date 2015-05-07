<?php
namespace greenbase;

header('Content-Type: application/json');
echo "{";
include_once 'Database.php';

// PHP code
function writeOrg($orgName, $id, $description, $orientation, $focus, $org_type) {
  echo("\"$id\": { \"id\": \"$id\", \"name\": \"$orgName\", \"logo\": \"./remoteimages/snapshot/logo_$id.png\", \"description\": \"$description\", \"orientation\": \"$orientation\", \"focus\": \"$focus\", \"org_type\": \"$org_type\"}");
}

$con = Database::getDefaultDBConnection();

$query = "SELECT orgs.id, name, headline, description, org_type, focus, logo_details.orientation, focus, org_type FROM orgs LEFT JOIN logo_details ON (orgs.id = logo_details.id) WHERE orgs.org_status = 1 AND logo_details.valid = 1";
$results = $con->query($query);
if (is_null($results) || false == $results) {
  exit ("Unable to access orgs:" . $con->error);
}

$first = true;
while($row = $results->fetch_assoc()) {
  if (!$first) {
    echo ",\n";
  }
  writeOrg($row["name"], $row["id"], $row["headline"], $row["orientation"], $row["focus"], $row["org_type"]);
  $first = false;
}
$results->free_result();
$con->close();
?>

}
