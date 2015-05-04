<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 5/1/15
 * Time: 10:03 AM
 */
namespace greenbase;
include 'database_init.php';

if (!isset($_GET["org_id"])) {
  echo "USAGE (for test): Tweet.php?org_id=x where x is your org id";
  return;
}
$org_id = intval($_GET["org_id"]);
echo "Org:" . $org_id;

$con = getDBConnection($db_config);

echo "<HTML><HEAD></HEAD><BODY>";
$tweets = Tweet::getTweetsForOrg($org_id, $con);
echo ("<UL>");
foreach ($tweets as $tweet) {
  echo ("<LI><A HREF='" . $tweet->userUrl . "'><IMG SRC='" . $tweet->userProfileImageUrl . "'></A> Tweeted ". $tweet->text . "<BR>" . $tweet->createdAt);
}
echo "</UL></BODY>";

class Tweet
{
  public $orgId, $createdAt, $text, $userProfileImageUrl, $userDescription, $userUrl;

  public static function getTweetsForOrg($orgId, $con)
  {
    $results = [];
    $query = "SELECT org_id, created_at, text, user_profile_image_url, user_description, user_url FROM twitter_feed WHERE org_id = ?";
    $stmt = $con->prepare($query);
    if ($stmt == null || $stmt == false) {
      echo "Null statement";
      return results;
    }
    $tweet = new Tweet();
    if ($stmt->bind_param("i", $orgId)) {
      if ($stmt->execute()) {
        $stmt->bind_result($tweet->orgId, $tweet->createdAt, $tweet->text, $tweet->userProfileImageUrl, $tweet->userDescription, $tweet->userUrl);
      } else {
        echo "Query failed";
        echo $con->error;
      }

      while ($stmt->fetch()) {
        echo("Fetching");
        $results[] = $tweet;
        $tweet = new Tweet();
        $stmt->bind_result($tweet->orgId, $tweet->createdAt, $tweet->text, $tweet->userProfileImageUrl, $tweet->userDescription, $tweet->userUrl);
      }
    } else {
      echo "Failure to bind";
    }
    return $results;
  }
}

?>