<?php
namespace greenbase;

include_once 'database_init.php';
include 'Organization.php';
include 'Event.php';

if (!isset($_GET['org_id'])) {
    exit ('No org id');
}
$org_id = $_GET['org_id'];

$con = getDBConnection($db_config);
$org = Organization::getOrg($org_id, $con);
if (is_null($org)) {
    echo "NULL ORG";
    return;
}

$events = Event::getEventsForOrg($org_id, $con);
?>

<img alt="<?php echo(htmlspecialchars($org->name)); ?> Logo" src="remoteimage.php?org=<?php echo ($org_id);?>">
<p ><?php echo (htmlspecialchars($org->name)); ?> (founded <?php echo($org->founding_year); ?>)</p>

<p><a href="<?php echo ($org->website);?>"><?php echo ($org->website);?></a></p>
<p>Address:</p>
<p>
<?php echo (htmlspecialchars($org->address));?><br>
<?php echo (htmlspecialchars($org->city));?>, <?php echo ($org->state);?><br>
<?php echo ($org->phone);?>
</p>
<p>
</p>


<blockquote>
    <?php echo(htmlspecialchars($org->headline)); ?>
</blockquote>
<div>
    <h2>Organization detailed description</h2>
    <?php echo (htmlspecialchars($org->description)); ?>
</div>
<div class="twitter-feed">
    <?php
    if (!is_null($events)) {
        foreach ($events as $event) {
            $event->printSimpleEventBlock();
            echo ("<br>");
        }
    }
    ?>
</div>
<?php
$con->close();
?>
				