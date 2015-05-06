<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/3/15
 * Time: 1:28 PM
 */

include_once 'Database.php';

$con = Database::getDefaultDBConnection();

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
  echo '  <option value="' . $value .'">' . $title . '</option>';
}
?>
  <div class="form-wrapper clearfix">
    <form class="form-contact submit-form">
      <input type="hidden" name="todo" value="register_org">
      <!--
          <form class="form-contact" action="register.php" method="post">
      -->
      <div class="inputs-wrapper">
        <input class="form-name validate-required" type="text" placeholder="Organization Name" name="name">
        <input class="form-name validate-required" type="text" min="1800" max="2015" placeholder="Year Founded"
               name="year_founded">
        <textarea class="form-message validate-required" name="headline"
                  placeholder="Organization Short Description (144 character limit)" maxlength="144"></textarea>
        <textarea class="form-message validate-required" name="description"
                  placeholder="Organization Extended Description"></textarea>
        <input class="form-name validate-required" type="text" placeholder="Address" name="address">
        <input class="form-name validate-required" type="text" placeholder="City" name="city">
        <input class="form-name validate-required" type="text" placeholder="State" name="state" maxlength="2">
        <input class="form-name validate-required" type="tel" placeholder="Phone Number" name="phone">
        <br>Digital contacts for the organization:<br>
        <input class="form-name validate-required" type="text" placeholder="Organization Website" name="website">
        <input class="form-name validate-required" type="text" placeholder="URL for the organization's logo" name="logo_url">
        <input class="form-name" type="text" placeholder="Twitter Handle" name="twitter">
        <input class="form-name" type="text" placeholder="Facebook Page" name="facebook">
        <br>.<br>Organization Type
        <select name="org_type">
          <?php
          writeOrgTypeList($con);
          ?>
        </select>
        <br>.<br>
        Area of Focus
        <select name="focus">
          <?php
          writeFocusList($con);
          ?>
        </select>
        <br>.<br>
        <input class="form-name validate-required" type="text" placeholder="Your first name" name="firstname">
        <input class="form-name validate-required" type="text" placeholder="Your last name" name="lastname">
        <input class="form-email validate-required validate-email" type="email" name="email"
               placeholder="Your Email Address">
        <br>
      </div>
      <input type="submit" class="send-form" value="Submit">

      <div class="form-success">
        <span class="text-white">Message sent - Thanks for your submission. Your confirmation code is:</span>
      </div>
      <div class="form-error">
        <span class="text-white">Please complete all fields correctly</span>
      </div>
      <div class="form-message">
      </div>
    </form>
  </div>