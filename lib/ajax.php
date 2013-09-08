<?php

//ini_set('display_errors', 'on');

require_once('FlightFare.php');
require_once('LookupAirport.php');
require_once('cache.php');

$cities = array(
		'berlin' => 'TXL',
		'chicago' => 'ORD',
		'miami' => 'MIA',
		'sydney' => 'SYD',
		'hong_kong' => 'HKG',
		'delhi' => 'DEL',
		'washington_dc' => 'DCA',
		'tallinn' => 'TLL',
		'san_francisco' => 'SFO',
		'honolulu' => 'HNL',
		'copenhagen' => 'CPH',
		'san_antonio' => 'SAT',
		'malta' => 'MLA',
		'saint_petersburg' => 'LED',
		'mexico' => 'MEX',
		'prague' => 'PRG',
		'phuket' => 'HKT',
		'iceland' => 'KEF',
		'new_orleans' => 'MSY',
		'cape_town' => 'CPT',
		'istanbul' => 'IST',
		'barcelona' => 'BCN',
		'scotland' => 'EDI',
		'cuba' => 'HAV',
		'tuscania' => 'PSA',
		'singapore' => 'SIN',
		'paris' => 'CDG',
		'dublin' => 'DUB',
		'dominican_republic' => 'POP',
		'puerto_rico' => 'SJU',
		'athens' => 'ATH',
		'rome' => 'FCO',
		'bangkok' => 'BKK',
		'seattle' => 'SEA',
		'moscow' => 'DME',
		'los_angeles' => 'LAX',
		'venice' => 'VNC',
		'munich' => 'MUC',
		'las_vegas' => 'LAS',
		'vancouver' => 'YVR',
		'london' => 'LHR',
		'lisbon' => 'LIS',
		'buenos_aires' => 'BAI',
		'naples' => 'RRO',
		'boston' => 'BOS',
		'budapest' => 'BUD',
		'rio_de_janeiro' => 'GIG',
		'newyork' => 'JFK',
		'cyprus' => 'NIC',
		'tokyo' => 'HND',
		'marrakech' => 'RAK',
		'algarve' => 'FAO',
		'crete' => 'HER',
		'madrid' => 'MAD',
		'gran_canaria' => 'LPA',
		'milan' => 'MXP',
		'vienna' => 'VIE',
		'seoul' => 'SEL',
		'amsterdam' => 'AMS',
		'toronto' => 'YYZ',
		);


$memcacheObj = Cache::getInstance();


function toto() {
  if (!isset($_POST['start_date']))
    $_POST['start_date'] = '09/11/2013';
  if (!isset($_POST['end_date']))
    $_POST['end_date'] = '09/21/2013';

  global $cities, $memcacheObj;

  $cacheKey = $_POST['city'].'|'.(isset($_POST['to']) ? '|'.$_POST['to'] : '');
  $buf = array();
  foreach ($cities as $city => $code) {
    if (!empty($code)) {
      $city2 = $code;
    } else {
      $city2 = $city;
    }

    $f = new FlightFare($_POST['city'], $_POST['start_date'], $city2, $_POST['end_date']);

    $ret = $f->search();

    if (!isset($ret[0][0][0]) || !isset($ret[1][0][0])) {
      file_put_contents($city, "KO");
      continue;
    }

    $leg1 = $ret[0][0][0];
    $leg2 = $ret[1][0][0];

    $buf[$city] = $leg1['price'];

    file_put_contents($city, "done");
  }
  $memcacheObj->set($cacheKey, json_encode($buf));
  return json_encode($buf);
}

class Ajax {
  public function __construct() {

    if (isset($_POST['data'])) {
      parse_str($_POST['data'], $tmp);
      $_POST['city'] = $tmp['city'];
      $_POST['budget'] = $tmp['budget'];
    }

    if ( isset( $_GET['action'] ) && $_GET['action'] == 'get_city_info' ) {
      $this->getCityInfo( $_GET['city'] );
    }
    if ( isset( $_POST['action'] ) && $_POST['action'] == 'get_meteo' ) {

      $this->getAllWeather();
    }
  }

  protected function getCityInfo( $city ) {
    $city = @file_get_contents( 'http://nico.suntrip.co/assets/cities/' . $city . '.html');
    echo $city;
    die();
  }

  protected function getAllWeather() {
    $meteoApi = Meteo::getInstance();
    $meteo = $meteoApi->get_global_data();

    //       $meteo = $meteoApi->getMeteo();
    //       $forecast = $meteoApi->getForecast();

    $ret = json_decode(toto(), true);

    foreach ($meteo as $key => $elem) {
      $n = trim($ret[$elem->origin_name], '$');
      $n = (int)str_replace(',', '', $n);
      if (isset($_POST['budget']) && $_POST['budget'] > 0 && isset($ret[$elem->origin_name]) && $ret[$elem->origin_name] > $_POST['budget']) {
	unset($meteo[$key]);
      }
    }

    $output = json_encode($meteo);

    //        echo $GLOBALS['meteo'];
    die($output);
  }
}

$ajax = new Ajax();