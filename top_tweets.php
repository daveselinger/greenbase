<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 5/7/15
 * Time: 2:41 PM
 */
namespace greenbase;

include_once 'Database.php';
include_once 'Tweet.php';
include 'get_config.php';

$con = Database::getDefaultDBConnection();
$tweets = Tweet::getRecentTweets($con);

echo Tweet::htmlizeTweets($tweets);
?>