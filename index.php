<?php

ini_set('display_errors', 'on');

include('lib/meteo.php');
require('lib/FlightFare.php');
require('lib/LookupAirport.php');

// phpinfo();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml" lang="en">
<head>
    <title>Sun Trip - The best trip ever</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" />
    <meta name='robots' content='noindex,nofollow' />
    <link rel='stylesheet' id='sun-trip'  href='/assets/css/style.css' type='text/css' media='all' />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/dist/css/bootstrap.css">
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script src="/assets/js/jquery-1.9.1.js"></script>
    <script src="/assets/js/jquery-ui.js"></script>
    <script src="/assets/js/sun-trip.js"></script>
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <div id="map-canvas"></div>


    <div id="right-rail">

  <div id="hello"></div>

        <?php require_once('./assets/cities/paris.html'); ?>
        <h1>SunTrip</h1>
        <H1>NOW</h1>
    <?php

        $meteoApi = Meteo::getInstance();
        $meteo = $meteoApi->getMeteo( true );
        $forecast = $meteoApi->getForecast( true );

        echo $meteo->current_observation->display_location->city;
    ?>
        <img src="<?php if ( isset( $meteo->current_observation->icon_url ) ) {  echo $meteo->current_observation->icon_url; } ?>">

	  <?php echo $meteo->current_observation->temperature_string; ?>

        <H1>FORECAST</h1>
        <?php
        foreach ($forecast->forecast->txt_forecast->forecastday as $d)
        {
    ?>

    <h2><?php echo $d->title;  ?></h2>
    <img src="<?php echo $d->icon_url ?>">
    <?php echo $d->fcttext; ?>
    <?php
    } ?>

    </div>

    <div id="search-bar" >

	  <form class="form-inline" role="form" action="" method="POST" id="search-form">

	  <div class="form-group">
	  <div id="logo"><img src="assets/img/logo.png" height="40px"></div>
	  </div>

<div class="form-group">
	  Departure city
</div>

<div class="form-group">
	  <input type="text" class="form-control" id="" placeholder="City" style="width: 60px;" name="city">
</div>

<div class="form-group">
	  Start date
</div>

<div class="form-group">
	  <label class="sr-only" for="exampleInputEmail2">Start date</label>
	  <input type="text" class="form-control" id="date-checkin" placeholder="Date" style="width: 100px;" name="start_date">
</div>

<div class="form-group">
	  End date
</div>

<div class="form-group">
	  <label class="sr-only" for="exampleInputEmail2">End date</label>
	  <input type="text" class="form-control" id="date-checkout" placeholder="Date"  style="width: 100px;" name="end_date">
</div>

<div class="form-group">
     <div class="thermometerlow">L</div>
</div>

<div class="form-group">
	  <label class="sr-only" for="exampleInputEmail2">Min</label>
	  <input type="text" class="form-control" id="exampleInputEmail2" placeholder="Min" style="width: 56px;" name="min">

</div>

<div class="form-group">
     <div class="thermometer">H</div>
</div>

<div class="form-group">

	  <input type="text" class="form-control" id="exampleInputEmail2" placeholder="Max" style="width: 56px;" name="max">

</div>

<div class="form-group">
  <input type='checkbox' name='sunonly' value='valuable' id="sunonly" name="sunonly"/><label for="sunonly"></label>
</div>

<div class="form-group" id="budget_div">
	  Budget
</div>

<div class="form-group">
	  <label class="sr-only" for="exampleInputEmail2">Budget</label>

	  <input type="text" class="form-control" id="exampleInputEmail2" placeholder="$$$" style="width: 60px;" name="budget">

</div>

  <button type="submit" class="btn btn-success" id="go_button">GO</button>

</form>

    </div>
    <div id="footer">Powered by <a target="_blank" href="http://www.wunderground.com/"><img src="assets/img/wunderground.png"></a> + <a target="_blank" href="http://www.pearson.com/"><img src="assets/img/pearson.png"></a> at <a target="_blank" href="http://techcrunch.com/events/disrupt-sf-2013/event-info/"><img src="assets/img/tc.png"></a> by <a target="_blank" href="http://www.while42.org">while42 SF team</a></div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-2927534-25', 'suntrip.co');
  ga('send', 'pageview');

</script>
</body>
</html>
