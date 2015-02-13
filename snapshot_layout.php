<?php
header('Content-Type: application/json');
?>
{
<?php
include 'database_init.php';
$con = getDBConnection($db_config);

//TODO: Move all this logic into a nonruntime function and store the layout in the database at some point.

/**
 * @param $con
 * @param $focus
 * @param $org_type
 * @return array
 */
function getAllOrgs(mysqli $con, $org_type, $focus)
{
  $orgs = [];
  $h = [];
  $v = [];
  $s = [];
  $total = 0;

  $query = "SELECT orgs.id, orientation FROM orgs LEFT JOIN logo_details ON (logo_details.id = orgs.id) " .
    "WHERE org_status = 1 AND logo_details.valid = 1 " .
    "AND org_type = ? AND focus = ? " .
    "ORDER BY orientation";

  $stmt = $con->prepare($query);
  if ($stmt == false) {
    echo($query . "\n");
    exit ($con->error());
  }

  $stmt->bind_param("ss", $org_type, $focus);
//  echo ($query." for org=$org_type, focus=$focus \n");

  $stmt->bind_result($id, $orientation);

  if (!$stmt->execute()) {
    exit ("Unable to access orgs from $focus , $org_type");
  }

  while ($stmt->fetch()) {
    $total++;
    if ($orientation == "horizontal") {
      $h[] = $id;
    } else if ($orientation == "up") {
      $v[] = $id;
    } else {
      $s[] = $id;
    }
  }

  $stmt->free_result();
  $orgs["horizontal"] = $h;
  $orgs["vertical"] = $v;
  $orgs["square"] = $s;
  $orgs["total"] = $total;
  return $orgs;
}

function writeList($name, $list)
{
  echo "\"".htmlspecialchars($name) ."\": [";
  $first = true;
  foreach ($list as $item) {
    if (!$first) {
      echo(",");
    }
    $first = false;
    echo "\"".htmlspecialchars($item)."\"";
  }
  echo "]";
}

function printLayout($focus, $total, $width, $height, $cell)
{
  echo "    \"".htmlspecialchars($focus) ."\": \n    [ $total, \n";
  for ($i = 0; $i < $height; $i++) {
    if ($i != 0) {
      echo ",\n";
    }
    echo "    [";

    $first_width = true;
    for ($j = 0; $j < $width; $j++) {
      if ($j!=0) {
        echo ",";
      }

      echo $cell[$i][$j];
    }
    echo "]";
  }
  echo "]";
}

// Now populate 2 local arrays (focus_list and org_type_list with these in the right order)
$focus_list = array();
$query = "SELECT focus FROM focus_list ORDER BY focus_order, focus";
$results = $con->query($query);
while ($row = $results->fetch_assoc()) {
  $focus_list[] = $row["focus"];
}
$results->free_result();
$org_type_list = array();
$query = "SELECT org_type FROM org_type_list ORDER BY org_type_order, org_type";
$results = $con->query($query);
while ($row = $results->fetch_assoc()) {
  $org_type_list[] = $row["org_type"];
}
$results->free_result();

writeList("orgs", $org_type_list);
echo ",\n";
writeList("focus_types", $focus_list);
echo ",\n\"layout\": {\n";

$first_org = true;
foreach ($org_type_list as $org_type) {
  if (!$first_org) {
    echo ",\n";
  }
  $first_org = false;
  echo("  \"". htmlspecialchars($org_type) ."\": {\n");

  $first_focus = true;
  foreach ($focus_list as $focus) {
    $orgs = getAllOrgs($con, $org_type, $focus);
    $total = $orgs["total"];
    $h = $orgs["horizontal"];
    $s = $orgs["square"];
    $v = $orgs["vertical"];
    // This is the resulting cell in a sparse matrix format. (indexed by row, then column, zero-based)
    $cell = [];
    $width = 0;
    $height = 0;

//    echo "Count $total H:" . count($h) . " V:" . count($v) ." S:" . count($s);

    if ($total == 0) {
      //Do nothing
      continue;
    } else if ($total == 1) {
      // Just write the one in the middle no matter what. Size = "4"
      $id = 0;
      if (count($h) > 0) {
        $id = $h[0];
      } else if (count($v) > 0) {
        $id = $v[0];
      } else {
        $id = $s[0];
      }

      $height = $width = 1;
      $row = [];
      $row["0"] = $id;
      $cell["0"] = $row;
    } else if ($total == 2) {
      $vertical = false;
      $first = 0;
      $second = 0;

      if (count($h) == 2) {
        $vertical = true;
        $first = $h[0];
        $second = $h[1];
      } else if (count($v) == 2) {
        $vertical = false;
        $first = $v[0];
        $second = $v[1];
      } else if (count($v) > 0) {
        $vertical = false;
        $first = $v[0];
        if (count($s) > 0) {
          $second = $s[0];
        } else {
          $second = $h[0];
        }
      } else if (count($h) > 0) {
        $vertical = true;
        $first = $h[0];
        $second = $s[0];
      } else {
        // All squares
        $vertical = true;
        $first = $s[0];
        $second = $s[1];
      }

      if ($vertical) {
        $width = 1;
        $height = 2;
        $row0 = [];
        $row0 ["0"] = $first;
        $cell["0"] = $row0;
        $row1 = [];
        $row1["0"] = $second;
        $cell["1"] = $row1;
      } else {
        $width = 2;
        $height = 1;
        $row0 = [];
        $row0 ["0"] = $first;
        $row0 ["1"] = $second;
        $cell["0"] = $row0;
      }
    } else {
      continue;
    }

    if (!$first_focus) {
      echo ",\n";
    }
    $first_focus = false;

    printLayout($focus, $total, $width, $height, $cell);
  }
  echo "  }";
}
$con->close();
?>

  }
}

