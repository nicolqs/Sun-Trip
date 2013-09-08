<?php

include 'lib/meteo.php';
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
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script src="/assets/js/jquery-1.9.1.js"></script>
    <script src="/assets/js/jquery-ui.js"></script>
    <script src="/assets/js/sun-trip.js"></script>
</head>
<body>
    <div id="map-canvas"></div>
    <div id="right-rail">
        <?php require_once('./pearson/cities/paris.html'); ?>
        <h1>SunTrip</h1>
        <H1>NOW</h1>
    <?php 

        $meteoApi = Meteo::getInstance();
        $meteo = $meteoApi->getMeteo( true );
        $forecast = $meteoApi->getForecast( true );

        echo $meteo->current_observation->display_location->city; 
    ?>
        <img src="<?php echo $meteo->current_observation->icon_url; ?>">

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
    <div id="search-bar">
        <form action="" method="POST" id="search-form">
            <fieldset>
                <legend>Search my trip</legend>
                <ul>
                    <li>
                        Check In: <input type="text" size="10" id="date-checkin">
                    </li>
                    <li>
                        Check Out: <input type="text" size="10" id="date-checkout">
                    </li>
                    <li>
                         Temperature min: <input type="text" size="10" value="75">
                    </li>
                    <li>
                         Max: <input type="text" size="10" value="90">
                    </li>
                    <li>
                         Max Budget: <input type="text" size="10" value="500">
                    </li>
                    <li>
                        <input type="submit">
                    </li>
                </ul>
              </fieldset>
        </form>
    </div>
</body>
</html>