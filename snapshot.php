<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>Greenbase - About</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link href="css/flexslider.min.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="css/line-icons.min.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="css/elegant-icons.min.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="css/lightbox.min.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
        <link href="css/theme.css" rel="stylesheet" type="text/css" media="all"/>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300,600,700%7CRaleway:700' rel='stylesheet' type='text/css'>
        <script src="js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
    	<div class="loader">
    		<div class="spinner">
			  <div class="double-bounce1"></div>
			  <div class="double-bounce2"></div>
			</div>
    	</div>
				<?php include '_templates/navigation.php'; ?>
			<div class="main-container">
		<section class="video-inline">
				<div class="container">
					<div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 text-center">
							<p class="lead space-bottom-medium">
								Short description. Visual presentation of the different organizations by Organization Type and Area of Focus
						  </p>
						</div>
					</div>
						
				  <div class="row"> <div class="col-sm-12">
          <p>
<?php
// PHP code
function writeOrg($orgName, $logoUrl, $description, $size) {
  echo "<SPAN TITLE=\"" . $description . "\"><IMG ALT=\"" . $orgName . "\" WIDTH=" . $size . " HEIGHT=" . $size . " SRC='" . $logoUrl . "'></SPAN>";
}
$con = new mysqli("mysql.climatebase.dreamhosters.com", "climatebase", "climatebas3");
if ($con->connect_error) {
	exit ('Connect error (' .mysqli_connect_errno() .') '.mysqli_connect_error());
} else {
}
$con->select_db("climatebase");
// Create a temporary table with the counts for each cell
$query = "CREATE TEMPORARY TABLE snapshot_counts AS SELECT org_type, focus, count(1) AS total FROM orgs GROUP BY org_type, focus";
if (!$con->query($query)) {
    exit ("Unable to make table");
}
if (!$con->query("ALTER TABLE snapshot_counts ADD INDEX (org_type, focus)")) {
  exit ("Unable to add index");
}
$focus_list = array();
$query = "SELECT focus FROM focus_list ORDER BY focus_order, focus";
$results = $con->query($query);
while ($row = $results->fetch_assoc()) {
  $focus_list[] = $row["focus"];
}
$results->free_result();
echo count($focus_list) . " focus areas<br>";
$org_type_list = array();
$query = "SELECT org_type FROM org_type_list ORDER BY org_type_order, org_type";
$results = $con->query($query);
while ($row = $results->fetch_assoc()) {
  $org_type_list[] = $row["org_type"];
}
$results->free_result();
echo count($org_type_list) . " org types<br>";
echo '<hr>';
$query = "CREATE TEMPORARY TABLE snapshot_data AS SELECT orgs.*, snapshot_counts.total FROM orgs LEFT JOIN snapshot_counts on (orgs.org_type = snapshot_counts.org_type AND orgs.focus = snapshot_counts.focus) ORDER BY orgs.org_type ASC, orgs.focus ASC";
if (!$con->query($query)) {
    exit ("Unable to make table");
}
if (!$con->query("ALTER TABLE snapshot_data ADD INDEX (org_type, focus)")) {
  exit ("Unable to add index");
}
$query = "SELECT name, logo_url, description, total FROM snapshot_data WHERE org_type = ? AND focus = ?";
$stmt = $con->prepare($query);
echo '<table>';
foreach($org_type_list as $org_type) {
  echo '<tr><td>' . $org_type . '</td>';
  foreach($focus_list as $focus) {
    echo '<td>';
    $stmt->bind_param("ss", $org_type, $focus);
    $count = 0;
    $stmt->execute();
    $stmt->bind_result($name, $logo_url, $description, $total);
    while ($stmt->fetch()) {
      $count++;
      $size = 100;
      $breakpoint = 1;
      if ($total >= 9) {
        $size = 50;
        $breakpoint = 3;
      } elseif ($total >= 4) {
        $size = 60;
        $breakpoint = 2;
      } elseif ($total > 2) {
        $size = 100;
        $breakpoint = 2;
      }
      writeOrg($name, $logo_url, $description, $size);
      if ($count == $breakpoint) {
        echo '<br>';
        $count = 0;
      }
    }
    echo '</td>';
  }
  echo '</tr>';
}
echo '<tr><td></td>';
foreach($focus_list as $focus) {
  echo '<td>' . $focus . '</td>';
}
echo '</tr></table>'; 
$con->close();
?>
</p>
        </div></div>       
				</div>
			</section>
		</div>
		
		<div class="footer-container">
		</div>
				
		<script src="https://www.youtube.com/iframe_api"></script>
		<script src="js/jquery.min.js"></script>
        <script src="js/jquery.plugin.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.flexslider-min.js"></script>
        <script src="js/smooth-scroll.min.js"></script>
        <script src="js/skrollr.min.js"></script>
        <script src="js/spectragram.min.js"></script>
        <script src="js/scrollReveal.min.js"></script>
        <script src="js/isotope.min.js"></script>
        <script src="js/twitterFetcher_v10_min.js"></script>
        <script src="js/lightbox.min.js"></script>
        <script src="js/jquery.countdown.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
				
