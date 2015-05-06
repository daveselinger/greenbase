<?php
namespace greenbase;

/**
 * Created by PhpStorm.
 * User: selly
 * Date: 1/29/15
 * Time: 1:11 PM
 */
// VERY IMPORTANT: using a value of 'localhost' directs MySQL to use a local socket. so use 127.0.0.1 instead.
class Config {
  public static $db_url = '127.0.0.1';
  public static $db_username = 'greenbase';
  public static $db_password = 'greenbase';
  public static $db_name = 'greenbase';
  public static $greenbase_root = '/~selly';
}
?>