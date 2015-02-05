<?php
include 'database_init.php';
if (!isset($_GET['org'])) {
  exit ('No org id');
}

$org_id = $_GET['org'];
$width = 100;

$con = getDBConnection($db_config);

$query = "SELECT name, org_status, founding_year, description, address, city, state, website, phone, org_type, focus FROM orgs WHERE id=?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $org_id);
$stmt->execute();
$stmt->bind_result($name, $org_status, $founding_year, $description, $address, $city, $state, $website, $phone, $org_type, $focus );

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
      <p class="lead"><?php echo ($name); ?> (founded <?php echo($founding_year); ?>)</p>

      <p><span><a href="<?php echo ($website);?>"><?php echo ($website);?></a></span></p>
      <p><span>Address:
          <?php echo ($address);?>,
          <?php echo ($city);?>, <?php echo ($state);?><br>
          <?php echo ($phone);?></span></p>
      <p>
        <?php echo($description); ?>
      </p>


      <blockquote>
        "possible tagline"
        <br>
        just placeholder can be removed
      </blockquote>

    </div>
  </div>
</div>
