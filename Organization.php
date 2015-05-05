<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 5/4/15
 * Time: 11:21 AM
 */

namespace greenbase;
include 'database_init.php';

if (!isset($_GET["org_id"])) {
  echo "USAGE (for test): Organization.php?org_id=x where x is your org id";
  return;
}
$org_id = intval($_GET["org_id"]);

$con = getDBConnection($db_config);

$org = Organization::getOrg($org_id, $con);

if (is_null($org)) {
  echo "NULL RESULTS";
} else {
  echo $org->id . ";" . $org->name . ";" . $org->headline . ";" . $org->org_status . ";" . $org->founding_year . ";" . $org->logo_url . ";" . $org->description . ";" . $org->address . ";" . $org->city . ";" . $org->state . ";" . $org->website . ";" . $org->email_suffix . ";" . $org->phone . ";" . $org->org_type . ";" . $org->focus . ";" . $org->subgroup . ";" . $org->twitter_handle . ";" . $org->facebook_page;
}

class Organization {
  public $id, $name, $headline, $org_status, $founding_year, $logo_url, $description, $address, $city, $state, $website, $email_suffix, $phone, $org_type, $focus, $subgroup, $twitter_handle, $facebook_page;

  public static function getOrg($orgId, $con)
  {
    $result = null;
    $query = "SELECT id, name, headline, org_status, founding_year, logo_url, description, address, city, state, website, email_suffix, phone, org_type, focus, subgroup, twitter_handle, facebook_page FROM orgs WHERE id = ? ";
    $stmt = $con->prepare($query);
    if ($stmt == null || $stmt == false) {
      echo "Oops! We had a problem: Null statement";
      return null;
    }
    $org = new Organization();
    if ($stmt->bind_param("i", $orgId)) {
      if ($stmt->execute()) {
        $stmt->bind_result($org->id, $org->name, $org->headline, $org->org_status, $org->founding_year, $org->logo_url, $org->description, $org->address, $org->city, $org->state, $org->website, $org->email_suffix, $org->phone, $org->org_type, $org->focus, $org->subgroup, $org->twitter_handle, $org->facebook_page);
      } else {
        echo "Oops! We had a problem: Query failed";
        echo $con->error;
      }

      if ($stmt->fetch()) {
        $result = $org;
      }
    } else {
      echo "Oops! We had a problem: Failure to bind";
    }
    $stmt->close();
    return $result;
  }

}