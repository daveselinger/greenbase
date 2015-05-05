<?php
include_once 'database_init.php';
include 'Organization.php';
include 'Event.php';

if (!isset($_GET['org_id'])) {
    exit ('No org id');
}
$org_id = $_GET['org_id'];

$con = getDBConnection($db_config);
$org = greenbase\Organization::getOrg($org_id, $con);
if (is_null($org)) {
    echo "NULL ORG";
    return;
}

$events = greenbase\Event::getEventsForOrg($org_id, $con);
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
    <!--            INSERT TWITTER FEED HERE SOON!
    <a class="twitter-timeline" href="https://twitter.com/<?php echo $org->twitter_handle; ?>" data-widget-id="575417537745174528">Tweets by @<?php echo $org->twitter_handle; ?></a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
-->
</div>
<?php
$con->close();
?>
				