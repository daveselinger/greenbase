<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/3/15
 * Time: 1:28 PM
 *
 * Register an individual user
 */

include 'database_init.php';

$con = getDBConnection($db_config);

// PHP code
function writeFocusList($con) {
  $query = "SELECT focus AS name, id FROM focus_list ORDER BY focus_order, focus";
  writeList($con, $query);
}

function writeOrgTypeList($con) {
  $query = "SELECT org_type AS name, id FROM org_type_list ORDER BY org_type_order, org_type";
  writeList($con, $query);
}

function writeList ($con, $query) {
  $results = $con->query($query);
  if ($results == false) {
    echo $con->error;
    exit ("ERROR");
  }
  while($row = $results->fetch_assoc()) {
    writeOptionRow($row["name"], $row["id"]);
  }
  $results->free_result();
}

function writeOptionRow($title, $value) {
  echo '  <option value="' . $value .'">' . $title . '</option>\n';
}
?>
  <div class="form-wrapper clearfix">
    <form class="form-contact submit-form">
      <input type="hidden" name="todo" value="register_user">
      <!--
          <form class="form-contact" action="register.php" method="post">
      -->
      <div class="inputs-wrapper">
        <input class="form-name validate-required" type="text" placeholder="First Name" name="first_name">
        <input class="form-name validate-required" type="text" placeholder="Last Name" name="last_name">
        <input class="form-email validate-required validate-email" type="email" name="email"
               placeholder="Your Email Address">
        <input class="form-name" type="text" placeholder="Your Twitter Handle (optional--but helpful!)" name="twitter">
        <input class="form-name" type="text" placeholder="Reason for joining--goals,vision,interests (optional)" name="reason">
      </div>
      <input type="submit" class="send-form" value="Submit">

      <div class="form-success">
        <span class="text-white">Message sent - Thank you for joining the fight!!! Your confirmation code is:</span>
      </div>
      <div class="form-error">
        <span class="text-white">Please complete all required fields correctly</span>
      </div>
      <div class="form-message">
      </div>
    </form>
  </div>