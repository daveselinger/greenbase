<?php
include 'database_init.php';
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

$con->close();
?>

<div class="row">

  <div class="col-sm-4 col-md-3">
    <div class="author-details no-pad-top">
      <img alt="Organization Logo" src="remoteimage.php?org=<?php echo ($org_id);?>">

    </div>
  </div>
  <div class="col-sm-8">
    <div class="article-body">
      <p class="lead"><?php echo (htmlspecialchars($name)); ?> (founded <?php echo($founding_year); ?>)</p>

      <p><span><a href="<?php echo ($website);?>"><?php echo ($website);?></a></span></p>
      <span><p class="lead">Address:</p>
      <p>
          <?php echo (htmlspecialchars($address));?><br>
          <?php echo (htmlspecialchars($city));?>, <?php echo ($state);?><br>
          <?php echo ($phone);?></span></p>
      <p>
      </p>


      <blockquote>
        <?php echo(htmlspecialchars($headline)); ?>
      </blockquote>

      <?php
      if (is_null($twitter_handle) || "" == $twitter_handle) {
        echo "<h2>Organization description</h2>";
        echo(htmlspecialchars($description));
      } else {
      ?>
        <section class="contact-thirds">
          <div class="container">
            <div class="row">
              <div class="col-sm-4">
                <h2>Organization detailed description</h2>
                <?php echo (htmlspecialchars($description)); ?>
              </div>
              <div class="col-sm-4">
<!--            INSERT TWITTER FEED HERE SOON!
                <a class="twitter-timeline" href="https://twitter.com/<?php echo $twitter_handle; ?>" data-widget-id="575417537745174528">Tweets by @<?php echo $twitter_handle; ?></a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
-->              </div>
            </div>
          </div>
        </section>
      <?php
      }
      ?>

    </div>
  </div>
</div>
