<?php
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
?>