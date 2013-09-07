<?php

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

  private $_cache = array();

  public function __construct($from, $fromDate, $to, $toDate) {
    $this->_from = LookupAirport::lookupAirport($from);
    $this->_to = LookupAirport::lookupAirport($to);
    $this->_fromDate = $fromDate;
    $this->_toDate = $toDate;
    $this->_cache[$this->_from.'|'.$this->_to.'|'.$this->_fromDate.'|'.$this->_toDate] = array();
    echo $this->_from.'|'.$this->_to.'|'.$this->_fromDate.'|'.$this->_toDate."\n";
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
    if (isset($this->_cache[$this->_from.'|'.$this->_to.'|'.$this->_fromDate.'|'.$this->_toDate]) &&  sizeof($this->_cache[$this->_from.'|'.$this->_to.'|'.$this->_fromDate.'|'.$this->_toDate]) > 0) {
      return $this->_cache[$this->_from.'|'.$this->_to.'|'.$this->_fromDate.'|'.$this->_toDate];
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

    $this->_cache[$this->_from.'|'.$this->_to.'|'.$this->_fromDate.'|'.$this->_toDate] = array($leg1, $leg2);
    return array($leg1, $leg2);
  }

  public function displayCheapest() {
    $ret = $this->search();
    print_r($ret[0][0][0]);
    print_r($ret[1][0][0]);
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
      $i++;
    }
    return array($ret, isset($results['continuationId']) ? $results['continuationId'] : NULL, $tripId);
  }
}
