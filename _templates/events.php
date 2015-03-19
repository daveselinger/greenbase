<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/19/15
 * Time: 10:40 AM
 */

class Event {
  public $id;
  public $datetime;
  public $organization_id;
  public $user_id;
  public $headline;
  public $event_text;
}

function getEventsForOrg($org_id, $con) {
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


function printSimpleEventBlock(Event $event) {
  if (!is_null($event->user_id)) {
    echo ("User (");
    echo ($event->user_id);
  } else {
    echo ("Organization (");
    echo($event->organization_id);
  }
  echo (") said ");
  echo htmlspecialchars($event->headline);
  echo (":");
  echo htmlspecialchars($event->event_text);
}
?>