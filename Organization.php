<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 5/4/15
 * Time: 11:21 AM
 */

namespace greenbase;
include_once 'Database.php';

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

  public static function getOrgs(\mysqli $con, $validFilter, $statusFilter) {
    $query = "SELECT orgs.id, name, headline, org_status, founding_year, orgs.logo_url, description, address, city, state, website, email_suffix, phone, org_type, focus, subgroup, twitter_handle, facebook_page FROM orgs LEFT JOIN logo_details ON (orgs.id = logo_details.id) WHERE org_status = ? and logo_details.valid = ? ORDER BY name";
    $stmt = $con->prepare($query);

    $result = [];
    $org = new Organization();
    if ($stmt->bind_param("ii", $statusFilter, $validFilter)) {
      if ($stmt->execute()) {
        $stmt->bind_result($org->id, $org->name, $org->headline, $org->org_status, $org->founding_year, $org->logo_url, $org->description, $org->address, $org->city, $org->state, $org->website, $org->email_suffix, $org->phone, $org->org_type, $org->focus, $org->subgroup, $org->twitter_handle, $org->facebook_page);
      } else {
        echo "Oops! We had a problem: Query failed";
        echo $con->error;
      }

      while ($stmt->fetch()) {
        $result [] = $org;
        $org = new Organization();
        $stmt->bind_result($org->id, $org->name, $org->headline, $org->org_status, $org->founding_year, $org->logo_url, $org->description, $org->address, $org->city, $org->state, $org->website, $org->email_suffix, $org->phone, $org->org_type, $org->focus, $org->subgroup, $org->twitter_handle, $org->facebook_page);
      }
    }
    return $result;
  }
}