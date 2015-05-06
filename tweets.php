<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 5/5/15
 * Time: 11:17 PM
 */
namespace greenbase;

include_once 'Database.php';
include_once 'Tweet.php';

if (!isset($_GET["org_id"])) {
  echo "USAGE (for test): tweets.php?org_id=x where x is your org id";
  return;
}
$org_id = intval($_GET["org_id"]);

$con = Database::getDefaultDBConnection();

echo "<link rel='stylesheet' type='text/css' href='css/twitter.css'>";
$tweets = Tweet::getTweetsForOrg($org_id, $con);
echo ("<UL>");
foreach ($tweets as $tweet) {
  echo ("<LI><A HREF='" . $tweet->userUrl . "'><IMG SRC='" . $tweet->userProfileImageUrl . "'></A> Tweeted <blockquote class='twitter-tweet'>". $tweet->getHtmlIzedText() . "</blockquote><BR>" . $tweet->createdAt);
}
echo "</UL>";

?>