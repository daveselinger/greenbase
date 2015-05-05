<?php
namespace greenbase;

if (file_exists('config.php')) {
  try {
    include 'config.php';
  } catch (Exception $e) {
  }
}
// Read the site-specific configuration next.
if (file_exists('../config.php')) {
  try {
    include '../config.php';
  } catch (Exception $e) {
  }
}

if (!isset($db_url) || !isset($db_username) || !isset($db_password) || !isset($db_name) || ($db_url == '')
  || ($db_username == '') || ($db_password == '')  || ($db_name == '') ) {
  exit( 'No database configuration in "config.php" or config file not found. Please place the config file in ./.. relative to the greenbase root directory');
}

$db_config = [];
$db_config["url"] = $db_url;
$db_config["username"] = $db_username;
$db_config["db"] = $db_name;
$db_config["password"] = $db_password;

?>