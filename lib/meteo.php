<?php

class Meteo {
	/**
	 * Singleton instance
	 */
	private static $_instance;

	/**
	 * Attribute
	 */
	protected $_api_key = 'a3377ceb5414edef';
	protected $_forecast_api_endpoint;
	protected $_meteo_api_endpoint;

	/**
	 * Singleton constructor
	 */
	private function __construct() {
		$this->_meteo_api_endpoint = 'http://api.wunderground.com/api/' . $this->_api_key . '/conditions/q/';
		$this->_forecast_api_endpoint = 'http://api.wunderground.com/api/' . $this->_api_key . '/forecast/q/';
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
		$meteo = file_get_contents( $this->_meteo_api_endpoint . CITY );
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
	 * return forecast JSON
	 */
	public function getForecast( $decode = false ) {
		$forecast = file_get_contents( $this->_forecast_api_endpoint . CITY );
		if ( $decode ) {
			$forecast = json_decode($forecast);
		}
		return $forecast;
	}

	/**
	 * return forecast JSON
	 */
	public function get_global_data()
	{
		$data = array();
		$yaml_cities = file_get_contents(CITIES_YML);
		$cities = Spyc::YAMLLoad($yaml_cities);
		$i = 0;
		foreach ($cities as $c)
		{
			$meteo = $this->get_meteo_by_zmw($c['zmw'],true);
			$meteo->zmw = $c['zmw'];
			$meteo->dataset = $c['dataset'];
			// $to_remove = array( "\n" => "", "\t" => "" );
			// $meteo = strtr( $meteo, $to_remove );
			$data[] = $meteo;
			// var_dump($meteo);
		}
		return ($data);
	}
}
