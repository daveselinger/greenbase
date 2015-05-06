<?php
namespace greenbase;

include_once 'Database.php';
include_once 'Organization.php';
include_once 'Event.php';
include 'get_config.php';

if (!isset($_GET["org_id"])) {
    echo "USAGE (for test): single_org.php?org_id=x where x is your org id";
    return;
}
$org_id = $_GET['org_id'];

$con = Database::getDefaultDBConnection();
$org = Organization::getOrg($org_id, $con);
if (is_null($org)) {
    echo "NULL ORG";
    return;
}

$events = Event::getEventsForOrg($org_id, $con);
?>

<img alt="<?php echo(htmlspecialchars($org->name)); ?> Logo" src="<?php echo Config::$greenbase_root ?>/localimage.php?org_id=<?php echo ($org_id);?>">
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
				