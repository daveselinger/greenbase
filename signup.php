<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>Greenbase</title>
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
							<div class="form-wrapper clearfix">
								<form class="form-contact email-form">
									<div class="inputs-wrapper">
<input class="form-name validate-required" type="text" placeholder="Organization Name" name="name">
<input class="form-name validate-required" type="number" min="1900" max="2015" placeholder="Year Founded" name="yearfounded">
<textarea class="form-message validate-required" name="message" placeholder="Organization Description (144 character limit)"  maxlength="144"></textarea>
<input class="form-name validate-required" type="text" placeholder="Address" name="address">
<input class="form-name validate-required" type="text" placeholder="City" name="city">
<input class="form-name validate-required" type="text" placeholder="State" name="state" maxlength="2">
<input class="form-name validate-required" type="tel" placeholder="Phone Number" name="phone">
<input class="form-email validate-required validate-email" type="email" name="email" placeholder="Email Address">
<input class="form-name validate-required" type="text" placeholder="Organization Website" name="website">
<br>.<br>Organization Type  
<select>
  <option value="Science and Research">Science and Research</option>
  <option value="Adaptation">Adaptation</option>
  <option value="NGO / Action and Awareness">NGO / Action and Awareness</option>
  <option value="Commercial">Commercial</option>
  <option value="Policy">Policy</option>
</select>
<br>.<br>
Area of Focus
<select>
  <option value="Energy / Emissions">Energy / Emissions</option>
  <option value="Agriculture / Land Use">Agriculture / Land Use</option>
  <option value="Transportation">Transportation</option>
  <option value="Population">Population</option>
  <option value="Manufacturing">Manufacturing</option>
</select>
<br>.<br>
<br>
									</div>
									<input type="submit" class="send-form" value="Submit">
									<div class="form-success">
										<span class="text-white">Message sent - Thanks for your enquiry</span>
									</div>
									<div class="form-error">
										<span class="text-white">Please complete all fields correctly</span>
									</div>
								</form>
							</div>
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
				