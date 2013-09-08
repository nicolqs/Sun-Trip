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

  if (!isset($_POST['budget']))
    $_POST['budget'] = 0;

  $_POST['budget'] = (int)$_POST['budget'];

  global $cities, $memcacheObj;

  $cacheKey = $_POST['city'].'|'.(isset($_POST['to']) ? '|'.$_POST['to'] : '');
  if ($c = $memcacheObj->get($cacheKey)) {
    return $c;
  }
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
//      file_put_contents($city, "KO");
      continue;
    }

    $leg1 = $ret[0][0][0];
    $leg2 = $ret[1][0][0];

    $buf[$city] = $leg1['price'];

//    file_put_contents($city, "done");
  }
  $memcacheObj->set($cacheKey, json_encode($buf));
  return json_encode($buf);
}

class Ajax
{
  public function __construct()
  {
    
    if (isset($_REQUEST['data']))
      {
	parse_str($_REQUEST['data'], $tmp);
	$_REQUEST['city'] = $tmp['city'];
	$_REQUEST['budget'] = $tmp['budget'];
      }

    if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'get_city_info' ) {
      $this->getCityInfo( $_REQUEST['city'], $_REQUEST['zmw'], $_REQUEST['date_start'], $_REQUEST['date_end'] );
    }
    if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'get_meteo' )
      {
	
	$this->getAllWeather();
      }
  }

  protected function getCityInfo( $city , $zmw, $date_start, $date_end)
  {
    $meteoApi = Meteo::getInstance();
    $weather_html = $meteoApi->city_to_html($date_start, $date_end, $zmw);
    $city = @file_get_contents( 'http://nico.suntrip.co/assets/cities/' . $city . '.html');
    echo $weather_html . $city;
    die();
  }

  protected function getAllWeather()
  {
    $meteoApi = Meteo::getInstance();
    
    $data = $_REQUEST['data'];

    $arr = array();
    $data = explode('&', $data);
    foreach ($data as $num => $d)
      {
	$tab = explode('=', $d);	
	$arr[$tab[0]] = $tab[1];
      }

    $budget = (int)$_REQUEST['budget'];

    $meteo = $meteoApi->get_filtered_data($arr['start_date'], $arr['end_date'], $arr['min'], $arr['max'], isset( $arr['sunonly'], $budget, $_REQUEST['city']) );

    $ret = json_decode(toto(), true);

    foreach ($meteo as $key => $elem) {
      if (!isset($ret[$elem->origin_name])) {
	continue;
      }
      $n = trim($ret[$elem->origin_name], '$');
      $n = (int)str_replace(',', '', $n);
      if ($n > 0 && isset($_POST['budget']) && $_POST['budget'] > 0 && $n > (int)$_POST['budget'])  {
	unset($meteo[$key]);
      }
    }

    $output = json_encode($meteo);

    //        echo $GLOBALS['meteo'];
    die($output);
  }
}

$ajax = new Ajax();