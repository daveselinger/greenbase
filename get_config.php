<?php
namespace greenbase;

if (file_exists('Config.php')) {
  try {
    include_once 'Config.php';
  } catch (Exception $e) {
  }
} else if (file_exists('../Config.php')) {
// Read the site-specific configuration next.
  try {
    include_once '../Config.php';
  } catch (Exception $e) {
  }
}

if (!isset(Config::$db_url) || !isset(Config::$db_username) || !isset(Config::$db_password) || !isset(Config::$db_name) || (Config::$db_url == '')
  || (Config::$db_username == '') || (Config::$db_password == '')  || (Config::$db_name == '') ) {
  exit( 'No database configuration in "Config.php" or config file not found. Please place the config file in ./.. relative to the greenbase root directory');
}
?>