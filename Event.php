<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 5/5/15
 * Time: 3:04 PM
 */
namespace greenbase;

class Event {
  public $id;
  public $datetime;
  public $organization_id;
  public $user_id;
  public $headline;
  public $event_text;

  public static function getEventsForOrg($org_id, $con) {
    $query = "SELECT id, datetime, organization_id, user_id, headline, event_text FROM events WHERE organization_id=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $org_id);
    $stmt->execute();
    $id = $datetime = $organization_id = $user_id = $headline = $event_text = null;
    $stmt->bind_result($id, $datetime, $organization_id, $user_id, $headline, $event_text );
    $result = [];
    while ($stmt->fetch()) {
      $event = new Event();
      $event->id = $id;
      $event->datetime=$datetime;
      $event->organization_id=$organization_id;
      $event->user_id=$user_id;
      $event->headline=$headline;
      $event->event_text=$event_text;
      $result[]= $event;
    }
    $stmt->close();
    return $result;
  }

  public function printSimpleEventBlock() {
    if (!is_null($this->user_id)) {
      echo ("User (");
      echo ($this->user_id);
    } else {
      echo ("Organization (");
      echo($this->organization_id);
    }
    echo (") said ");
    echo htmlspecialchars($this->headline);
    echo (":");
    echo htmlspecialchars($this->event_text);
  }
}

?>