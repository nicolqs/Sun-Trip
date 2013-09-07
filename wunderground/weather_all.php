<?php

require_once('../lib/yml/spyc.php');

$yaml_cities_file = 'yaml_test.yml';

$yaml_cities = file_get_contents($yaml_cities_file);
$cities = Spyc::YAMLLoad($yaml_cities);

$API_KEY = 'a3377ceb5414edef';
//$FORECAST_API_ENDPOINT = "http://api.wunderground.com/api/" . $API_KEY . "/forecast/q/";
$METEO_API_ENDPOINT = "http://api.wunderground.com/api/" . $API_KEY . "/conditions/q/";

foreach ($cities as $c)
{
	$url = $METEO_API_ENDPOINT . 'zmw:' . $c['zmw'] . '.json';
	$json_data = file_get_contents($url);
	$data = json_decode($json_data);
	print_r($data);
}
