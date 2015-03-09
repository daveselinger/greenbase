<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/3/15
 * Time: 1:27 PM
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <title>Greenbase Sign Up</title>
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
  <section class="contact-center">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 text-center">
          <h1>Add to Greenbase</h1>
          <p class="lead">
            Help us provide an up-to-date visualization of organizations in the climate change mitigation & adaptation ecosystems. If you know of an organization we’re missing, please submit them for consideration.
        </div></p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <?php
        include 'register_logic.php';
        ?>
      </div>
    </div>
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
