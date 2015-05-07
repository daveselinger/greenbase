<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 5/1/15
 * Time: 10:03 AM
 */
namespace greenbase;
include_once 'Database.php';

// Note this works, but could be replaced with the better one found here: https://mathiasbynens.be/demo/url-regex, but needs to be really really tested: _^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_iuS
define ('URL_REGEX', "{(http|https|ftp)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*}");
define ('URL_REPLACEMENT', '<a href="$0">$0</a>');

define ('TWITTER_HANDLE_REGEX', "/@([A-Za-z0-9_]{1,15})/");
define ('URL_REPLACEMENT', '<a href="http://twitter.com/$1">$0</a>');


class Tweet
{
  public $orgId, $createdAt, $text, $userProfileImageUrl, $userDescription, $userUrl;

  public function getHtmlIzedText() {
    return preg_replace(URL_REGEX, URL_REPLACEMENT, $this->text);
  }

  public static function getTweetsForOrg($orgId, $con)
  {
    $results = [];
    $query = "SELECT org_id, created_at, text, user_profile_image_url, user_description, user_url FROM twitter_feed WHERE org_id = ? ORDER BY created_at DESC";
    $stmt = $con->prepare($query);
    if ($stmt == null || $stmt == false) {
      echo "Oops! We had a problem: Null statement";
      return results;
    }
    $tweet = new Tweet();
    if ($stmt->bind_param("i", $orgId)) {
      if ($stmt->execute()) {
        $stmt->bind_result($tweet->orgId, $tweet->createdAt, $tweet->text, $tweet->userProfileImageUrl, $tweet->userDescription, $tweet->userUrl);
      } else {
        echo "Oops! We had a problem: Query failed";
        echo $con->error;
      }

      while ($stmt->fetch()) {
        $results[] = $tweet;
        $tweet = new Tweet();
        $stmt->bind_result($tweet->orgId, $tweet->createdAt, $tweet->text, $tweet->userProfileImageUrl, $tweet->userDescription, $tweet->userUrl);
      }
    } else {
      echo "Oops! We had a problem: Failure to bind";
    }
    $stmt->close();
    return $results;
  }
}

?>