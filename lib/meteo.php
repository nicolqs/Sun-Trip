<?php
const API_KEY = 'a3377ceb5414edef';
const CITY = 'CA/San_Francisco.json';

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
	public function getMeteo() {
		$meteo = file_get_contents( $this->_meteo_api_endpoint . CITY );
		return json_decode($meteo);
	}

	/**
	 * return forecast JSON
	 */
	public function getForecast() {
		$forecast = file_get_contents( $this->_forecast_api_endpoint . CITY );
		return json_decode($forecast);
	}
}
