<?php

class LookupAirport {
  private static $_disabled = true;

  public function __construct() {
  }

  public static function lookupAirport($name) {
    if (($code = LookupAirport::_lookupAirportCode($name)) == NULL) {
      if (($code = LookupAirport::_lookupAirportName($name)) == NULL) {
	return NULL;
      }
    }
    return $code;
  }

  protected static function _lookupAirportCode($code) {
    if (self::$_disabled) {
      return $code;
    }
    $ret = @file_get_contents($url = 'http://airportcode.riobard.com/airport/'.urlencode($code).'?fmt=JSON');
    $ret = json_decode($ret, true);
    if ($ret && isset($ret['code']))
      return $ret['code'];
    return NULL;
  }

  protected static function _lookupAirportName($name) {
    if (self::$_disabled) {
      return $code;
    }
    $ret = @file_get_contents('http://airportcode.riobard.com/search?q='.urlencode($name).'&fmt=JSON');
    $ret = json_decode($ret, true);
    if ($ret && isset($ret[0]['code']))
      return $ret[0]['code'];
    return NULL;
  }
}
