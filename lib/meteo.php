<?php

API_KEY = 'a3377ceb5414edef';
METEO_API_ENDPOINT = "http://api.wunderground.com/api/" . API_KEY . "/conditions/q/";
FORECAST_API_ENDPOINT = "http://api.wunderground.com/api/" . API_KEY . "/forecast/q/";

CITY = 'CA/San_Francisco.json';

class Meteo {
	/**
	 * Singleton instance
	 */
	private static $_instance;

	/**
	 * Singleton constructor
	 */
	private function __construct() {

	}
	
	/**
	 * Singleton not clonable
	 */
	private function __clone() {}

	/**
	 * get singleton instance
	 */
	public static function getInstance() {
		if ( ! isset( $this->_instance ) ) {
			self::$_instance = new Meteo();
		}

		return self::$_instance;
	}

	/**
	 * return meteo JSON
	 */
	public function getMeteo() {
		$meteo = file_get_contents( METEO_API_ENDPOINT . CITY );
		return json_decode($meteo);
	}

	/**
	 * return forecast JSON
	 */
	public function getForecast() {
		$forecast = file_get_contents( FORECAST_API_ENDPOINT . CITY );
		return json_decode($forecast);
	}
}
