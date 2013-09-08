<?php

require_once('cache.php');

class FlightFare {
  protected $_from = NULL;
  protected $_to = NULL;
  protected $_fromDate = NULL;
  protected $_toDate = NULL;
  protected $_adults = 1;
  protected $_children = 0;
  protected $_seniors = 0;

  private $_fromTime = 'TANYT';
  private $_toTime = 'TANYT';
  private $_flightType = "roundtrip";
  private $_cacheKey = NULL;

  private $_cache = array();
  private $_memcacheObj = NULL;

  public function __construct($from, $fromDate, $to, $toDate) {
    // Connect to memcache
    $this->_memcacheObj = Cache::getInstance();

    // Set local members
    $this->_from = LookupAirport::lookupAirport($from);
    $this->_to = LookupAirport::lookupAirport($to);
    $this->_fromDate = $fromDate;
    $this->_toDate = $toDate;

    $this->_cacheKey = $this->_from.'|'.$this->_to.'|'.$this->_fromDate.'|'.$this->_toDate.'1';
    if ($c = $this->_memcacheObj->get($this->_cacheKey)) {
      $this->_cache[$this->_cacheKey] = unserialize($c);
    } else {
      $this->_cache[$this->_cacheKey] = array();
    }
  }

  public function setAdults($n) {
    $this->_adults = $n;
  }

  public function setChildren($n) {
    $this->_children = $n;
  }

  public function setSeniors($n) {
    $this->_seniors = $n;
  }

  public function search() {
    if (isset($this->_cache[$this->_cacheKey]) &&  sizeof($this->_cache[$this->_cacheKey]) > 0) {
      return $this->_cache[$this->_cacheKey];
    }

    $url = 'http://www.expedia.com/Flights-Search?trip='.$this->_flightType.
      '&leg1=from:'.$this->_from.',to:'.$this->_to.',departure:'.$this->_fromDate.$this->_fromTime.
      '&leg2=from:'.$this->_to.',to:'.$this->_from.',departure:'.$this->_toDate.$this->_toTime.
      '&passengers=children:'.$this->_children.',adults:'.$this->_adults.',seniors:'.$this->_seniors.',infantinlap:Y&mode=search';
    $searchResult = file_get_contents($url);
    if (!preg_match('#<FORM NAME="flightResultForm" method="post" action="/Flights-Search-RoundTrip\?c=(.*)&"  id="flightResultForm"#', $searchResult, $matches)) {
      return NULL;
    }
    $key = $matches[1];
    $leg1 = $this->_getResults('leg1', $key);
    $leg2 = $this->_getResults('leg2', $leg1[1], $leg1[2]);

    $this->_cache[$this->_cacheKey] = array($leg1, $leg2);

    $this->_memcacheObj->set($this->_cacheKey, serialize($this->_cache[$this->_cacheKey]));
    return $this->_cache[$this->_cacheKey];
  }

  public function getCheapest() {
    $ret = $this->search();

    if (!isset($ret[0][0][0]) || !isset($ret[1][0][0])) {
      return NULL;
    }

    $leg1 = $ret[0][0][0];
    $leg2 = $ret[1][0][0];

    $buf = '<div>';
    $buf .= '<strong><div id="price">'.$leg1['price'].'</div></strong> with <strong>'.
      $leg1['airlineName'].'</strong> '.
      $leg1['segments'][0]['departureAirport'].' <=> '.$leg1['segments'][sizeof($leg1['segments']) - 1]['arrivalAirport'].
      ' ('.(sizeof($leg1['segments']) - 1).' stop'.(sizeof($leg1['segments']) > 2 ? 's' : '').')<br />';

    $buf .= 'From '.$leg1['segments'][0]['departureDate'].' to '.$leg1['segments'][sizeof($leg1['segments']) - 1]['arrivalDate'];
    $buf .= '</div>';

    return $buf;
  }

  public function getTicketURL() {
    return 'http://www.expedia.com/Flights-Search?trip='.$this->_flightType.
      '&leg1=from:'.$this->_from.',to:'.$this->_to.',departure:'.$this->_fromDate.$this->_fromTime.
      '&leg2=from:'.$this->_to.',to:'.$this->_from.',departure:'.$this->_toDate.$this->_toTime.
      '&passengers=children:'.$this->_children.',adults:'.$this->_adults.',seniors:'.$this->_seniors.',infantinlap:Y&mode=search';
  }

  protected function _getResults($type, $key, $tripId = NULL) {
    switch ($type) {
    case "leg1":
      $results = file_get_contents('http://www.expedia.com/Flight-Search-Outbound?c='.$key.'&_='.time());
      break;
    case "leg2":
      $results = file_get_contents('http://www.expedia.com/Flight-Search-Update?c='.$key.'&tripId0='.$tripId.'&_='.time());
      break;
    default:
      die('Invalid type');
    }

    // Sanitize the result
    $results = trim(stripslashes($results), '"');

    // To array
    $results = json_decode($results, true);

    $tripId = 0;

    $ret = array();
    $i = 0;
    foreach ($results['searchResultsModel']['offers'] as $offer) {
      if (!$tripId || empty($tripId)) {
	$tripId = $offer['tripId'];
      }
      $l = $offer['legs'][0];
      $ret[$i] = array();
      $ret[$i]['airlineName'] = $l['carrier']['airlineName'];
      $ret[$i]['price'] = $l['price']['formattedTotalPrice'];
      $ret[$i]['segments'] = array();
      $j = 0;
      foreach ($l['segments'] as $segment) {
	$ret[$i]['segments'][$j]['departureAirport'] = $segment['departureAirport']['airportName'];
	$ret[$i]['segments'][$j]['departureDate'] = $segment['departureDate'];
	$ret[$i]['segments'][$j]['departureTime'] = $segment['departureTime'];
	$ret[$i]['segments'][$j]['arrivalAirport'] = $segment['arrivalAirport']['airportName'];
	$ret[$i]['segments'][$j]['arrivalDate'] = $segment['arrivalDate'];
	$ret[$i]['segments'][$j]['arrivalTime'] = $segment['arrivalTime'];
	$j++;
      }
      if (sizeof($ret[$i]) > 0 && sizeof($ret[$i]['segments']) > 0 && $ret[$i]['airlineName'] != '-1') {
	$i++;
      }
    }
    return array($ret, isset($results['continuationId']) ? $results['continuationId'] : NULL, $tripId);
  }
}
