<?php

class Meteo {
	/**
	 * Singleton instance
	 */
	private static $_instance;

	/**
	 * Attribute
	 */
	//	protected $_api_key = 'a3377ceb5414edef';
	protected $_api_key = 'fa55ab773cbb5883';
	protected $_forecast_api_endpoint;
	protected $_meteo_api_endpoint;
	protected $_origin_name;

	/**
	 * Singleton constructor
	 */
	private function __construct() {
		$this->_meteo_api_endpoint = 'http://api.wunderground.com/api/' . $this->_api_key . '/conditions/q/';
		$this->_forecast_api_endpoint = 'http://api.wunderground.com/api/' . $this->_api_key . '/forecast/q/';
		$this->_planner_api_endpoint = 'http://api.wunderground.com/api/' . $this->_api_key . '/planner_MMDDMMDD/q/';
	}

	/**
	 * Singleton not clonable
	 */
	private function __clone() {}

	/**
	 * get singleton instance
	 */
	public static function getInstance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new Meteo();
		}

		return self::$_instance;
	}

	/**
	 * return meteo JSON
	 */
	public function getMeteo( $decode = false ) {
		$meteo = file_get_contents( $this->_meteo_api_endpoint );
		if ( $decode ) {
			$meteo = json_decode($meteo);
		}
		return $meteo;
	}

	/**
	 * return meteo JSON by zmw
	 */
	public function get_meteo_by_zmw($zmw, $decode = false)
	{
		$meteo = file_get_contents( $this->_meteo_api_endpoint .  'zmw:' . $zmw . '.json');
		if ($decode)
		{
			$meteo = json_decode($meteo);
		}
		return $meteo;
	}

	/**
	 * return "planner" meteo JSON by zmw
	 * $dates = MMDDMMDD
	 */
	public function get_planner_by_dates_zmw($dates, $zmv, $decode = false)
	{
	  $endpoint = str_replace('MMDDMMDD', $dates, $this->_planner_api_endpoint);
	  $url = $endpoint .  'zmw:' . $zmv . '.json';
	  $meteo = file_get_contents($url);
	  if ($decode)
	    {
	      $meteo = json_decode($meteo);
	    }
	  return $meteo;
	}

	/**
	 * return forecast JSON
	 */
	public function getForecast( $decode = false ) {
		$forecast = file_get_contents( $this->_forecast_api_endpoint );
		if ( $decode ) {
			$forecast = json_decode($forecast);
		}
		return $forecast;
	}

	public function get_all_cities()
	{	  
	  $data = array();
	  $yaml_cities = file_get_contents(CITIES_YML);
	  $cities = Spyc::YAMLLoad($yaml_cities);
	  return ($cities);
	}

	/**
	 * return forecast JSON
	 */
	public function get_global_data()
	{
	  $cities = $this->get_all_cities();
	  foreach ($cities as $c)
	    {
	      $meteo = $this->get_meteo_by_zmw($c['zmw'], true);
	      $meteo->zmw = $c['zmw'];
	      $meteo->dataset = $c['dataset'];
	      $data[] = $meteo;
	    }
	  return ($data);
	}

	/**
	 * p is stdclass returned from api after a call to planner
	 */
	public function get_avg_temp_from_planner($p)
	{
	  $temp_high = $p->trip->temp_high->avg->F;
	  return ($temp_high);
	  $temp_low = $p->trip->temp_low->avg->F;
	  $avg = intval( ((int)$temp_high + (int)$temp_low ) / 2);
	  return ($avg);
	}
 
	public function get_filtered_data($date_start, $date_end, $min, $max, $sunonly = false)
	{
	  // check if it is already cached
	  $key = 'planner_' . $date_start . $date_end . '_' . $min . '_' . $max . '_' . (($sunonly) ? 'true' : 'false');
	  $cache = Cache::getInstance();
	  if (($data = $cache->get($key)))
	    {
	      return ($data);
	    }
	  //
	  $data = array();
	  $cities = $this->get_all_cities();
	  $i = 0;
	  foreach ($cities as $c)
	    {
	      $meteo = $this->get_planner_by_dates_zmw($date_start . $date_end, $c['zmw'], true);
	      //echo $c['dataset'] . ':' . $meteo->trip->cloud_cover->cond . "\n";
	      $avg_temp = $this->get_avg_temp_from_planner($meteo);
	      if ($avg_temp < $min || $avg_temp > $max)
		{
<<<<<<< variant A
		  continue;
>>>>>>> variant B
			$meteo = $this->get_meteo_by_zmw($c['zmw'], true);
			$meteo->zmw = $c['zmw'];
			$meteo->dataset = $c['dataset'];
			$meteo->origin_name = $key;
			$data[] = $meteo;
####### Ancestor
			$meteo = $this->get_meteo_by_zmw($c['zmw'],true);
			$meteo->zmw = $c['zmw'];
			$meteo->dataset = $c['dataset'];
			// $to_remove = array( "\n" => "", "\t" => "" );
			// $meteo = strtr( $meteo, $to_remove );
			$data[] = $meteo;
			// var_dump($meteo);
======= end
		}
	      if ($sunonly)
		{
		  if (strstr($meteo->trip->cloud_cover->cond, 'unny') == FALSE)
		    {
		      continue;
		    }
		}
	      //	      echo '[+] ADDING ' . $c['dataset'] . "\n";

              $meteo->zmw = $c['zmw'];
              $meteo->dataset = $c['dataset'];
              $data[] = $meteo;

	      // if ($i++ > 10) break;

	    }
	  $cache->set($key, $data);
	  return ($data);
	}
}
