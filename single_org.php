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

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>Greenbase: <?php echo($name); ?>details</title>
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
        <?php include 'google_analytics_include.php'; ?>
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
		<section class="article-single">
				<div class="container">
            <div class="row">

                <div class="col-sm-4 col-md-3">
                    <div class="author-details no-pad-top">
                        <img alt="<?php echo(htmlspecialchars($name)); ?> Logo" src="remoteimage.php?org=<?php echo ($org_id);?>">

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
				</div>
			</section>
		</div>

    <?php include '_templates/footer.php'; ?>

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
				