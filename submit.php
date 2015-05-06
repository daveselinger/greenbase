<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/9/15
 * Time: 2:58 PM
 *
 *
 * This is the generic form submission gateway whenever using the class "submit-form"
 * It should return one of 2 things:
 *  1. A number signalling success and the number of rows submitted or a confirmation code
 *  2. An error message
 */

include 'database_init.php';
$con = Database::getDefaultDBConnection();

if (!isset($_POST["todo"])) {
  $message = 'Failed to submit form: ';
  foreach ($_POST as $key => $value) {
    $message = $message . "$key=$value;";
  }
  error_log($message);
  exit ("Form submission failed. We are logging this error and will get back to you ASAP. Our apologies for any inconvenience.");
}
$todo = $_POST["todo"];

function createOrg(mysqli $con, $name, $headline, $org_status, $founding_year, $logo_url, $description, $address, $city, $state, $website, $phone, $org_type, $focus, $twitter_handle, $facebook_page)
{
//  echo ("Values: $name, $headline, $org_status, $founding_year, $logo_url, $description, $address, $city, $state, $website, $phone, $org_type, $focus, $twitter_handle, $facebook_page");
//  exit;

  $query = "INSERT INTO orgs (name, headline, org_status, founding_year, logo_url, description, address, city, state, website, phone, org_type, focus, twitter_handle, facebook_page) ".
    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $con->prepare($query);
  $stmt->bind_param("ssiisssssssssss", $name, $headline, $org_status, $founding_year, $logo_url, $description, $address, $city, $state, $website, $phone, $org_type, $focus, $twitter_handle, $facebook_page);
  if (!$stmt->execute()) {
    error_log($con->error. "Unable to create organization: $name, $headline, $org_status, $founding_year, $logo_url, $description, $address, $city, $state, $website, $phone, $org_type, $focus, $twitter_handle, $facebook_page");
    return "Form submission failed. We are logging this error and will get back to you ASAP. Our apologies for any inconvenience.";
  }
  return $con->insert_id;
}

function storeContact(mysqli $con, $name, $reason, $email, $message) {
  $query = "INSERT INTO contact (name, reason, email, message) ".
    "VALUES (?, ?, ?, ?)";
  $stmt = $con->prepare($query);
  $stmt->bind_param("ssss", $name, $reason, $email, $message);
  if (!$stmt->execute()) {
    error_log($con->error. "Unable to create contact: $name, $reason, $email, $message");
    return "Form submission failed. We are logging this error and will get back to you ASAP. Our apologies for any inconvenience.";
  }
  return $con->insert_id;
}

/**
 * BEGIN CORE EXECUTION BLOCK
 */

$result = "";
if ("register_org" == $todo) {
  //Process the add organization form
  if (!(isset($_POST["org_type"]) && isset($_POST["focus"]))) {
    echo("INCOMPLETE POST PARAMETERS:" . getPostParameters($_POST));
    exit;
  }
  $org_type = getOrgType($con, $_POST["org_type"]);
  $focus = getFocus($con, $_POST["focus"]);

  if (is_null($org_type) || is_null($focus)) {
    error_log("NULL ORG OR FOCUS:" . getPostParameters($_POST));
    echo "Apologies we've had an error. We are logging this for investigation and will get back to you promptly";
    exit;
  }

  $result = "" . (createOrg($con, $_POST["name"], $_POST["headline"], 0, $_POST["year_founded"], $_POST["logo_url"],
      $_POST["description"], $_POST["address"], $_POST["city"], $_POST["state"], $_POST["website"], $_POST["phone"],
      $org_type, $focus, $_POST["twitter"], $_POST["facebook"]));
} else if ("register_user" == $todo) {
  // Process the add a user form
  $result=1;
} else if ("contact" == $todo) {
  $result = "". storeContact($con, $_POST["name"], $_POST["reason"], $_POST["email"], $_POST["message"]);
}

$con->close();

echo $result;

/**
 * BEGIN HELPER FUNCTIONS
 */

function getPostParameters($post) {
  $message = '';
  foreach ($post as $key => $value) {
    $message = $message . "$key=$value;";
  }
  return $message;
}

function getFocus(mysqli $con, $id)
{
  $query = "SELECT focus AS name FROM focus_list WHERE id = ?";
  return getName($con, $query, $id);
}

function getOrgType(mysqli $con, $id) {
  $query = "SELECT org_type AS name FROM org_type_list WHERE id = ?";
  return getName ($con, $query, $id);
}

function getName(mysqli $con, $query, $id) {
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $id);
  $result = $stmt->execute();
  if ($result) {
    $stmt->bind_result($response);
    $stmt->fetch();
  } else {
    return null;
  }
  $stmt->free_result();
  return $response;
}
?>
