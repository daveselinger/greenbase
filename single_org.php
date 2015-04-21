<?php
include 'database_init.php';
include "_templates/events.php";

if (!isset($_GET['org'])) {
    exit ('No org id');
}

$org_id = $_GET['org'];
$width = 100;

$con = getDBConnection($db_config);

$query = "SELECT name, org_status, founding_year, headline, description, address, city, state, website, phone, org_type, focus, twitter_handle, facebook_page FROM orgs WHERE id=?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $org_id);
$stmt->execute();
$stmt->bind_result($name, $org_status, $founding_year, $headline, $description, $address, $city, $state, $website, $phone, $org_type, $focus, $twitter_handle, $facebook_page );

if (!$stmt->fetch()) {
    exit ("Invalid org");
}
$stmt->close();

$events = getEventsForOrg($org_id, $con);
?>

<img alt="<?php echo(htmlspecialchars($name)); ?> Logo" src="remoteimage.php?org=<?php echo ($org_id);?>">
<p ><?php echo (htmlspecialchars($name)); ?> (founded <?php echo($founding_year); ?>)</p>

<p><a href="<?php echo ($website);?>"><?php echo ($website);?></a></p>
<p>Address:</p>
<p>
<?php echo (htmlspecialchars($address));?><br>
<?php echo (htmlspecialchars($city));?>, <?php echo ($state);?><br>
<?php echo ($phone);?>
</p>
<p>
</p>


<blockquote>
    <?php echo(htmlspecialchars($headline)); ?>
</blockquote>
<div>
    <h2>Organization detailed description</h2>
    <?php echo (htmlspecialchars($description)); ?>
</div>
<div class="twitter-feed">
    <?php
    if (!is_null($events)) {
        foreach ($events as $event) {
            printSimpleEventBlock($event);
            echo ("<br>");
        }
    }
    ?>
    <!--            INSERT TWITTER FEED HERE SOON!
    <a class="twitter-timeline" href="https://twitter.com/<?php echo $twitter_handle; ?>" data-widget-id="575417537745174528">Tweets by @<?php echo $twitter_handle; ?></a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
-->
</div>
<?php
$con->close();
?>
				