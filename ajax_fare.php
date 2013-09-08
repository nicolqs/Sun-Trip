<?php

if (!isset($_POST['to'])) {
  header('Content-Type: application/json');
}

//ini_set('display_errors', 'on');

require('lib/FlightFare.php');
require('lib/LookupAirport.php');
require_once('lib/cache.php');

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

$cacheKey = $_POST['from'].'|'.$_POST['fromDate'].'|'.$_POST['toDate'].(isset($_POST['to']) ? '|'.$_POST['to'] : '').'5';
if ($c = $memcacheObj->get($cacheKey)) {
  echo $c;
  exit;
}

if (isset($_POST['to'])) {

  if (isset($cities[$_POST['to']])) {
    $_POST['to'] = $cities[$_POST['to']];
  }

  $f = new FlightFare($_POST['from'], $_POST['fromDate'], $_POST['to'], $_POST['toDate']);
  echo $f->getCheapest();
  echo '<br /><a id="buyit" target="_blank" href="'.$f->getTicketURL().'">Buy it!!!!!!!</a>';

} else {

  $buf = array();
  foreach ($cities as $city => $code) {
    if (!empty($code)) {
      $city2 = $code;
    } else {
      $city2 = $city;
    }

    $f = new FlightFare($_POST['from'], $_POST['fromDate'], $city2, $_POST['toDate']);

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
  echo json_encode($buf);
  $memcacheObj->set($cacheKey, json_encode($buf));

}
