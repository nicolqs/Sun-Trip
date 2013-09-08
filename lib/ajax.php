<?php

class Ajax {
	public function __construct() {

		if ( isset( $_GET['action'] ) && $_GET['action'] == 'get_city_info' ) {
			$this->getCityInfo( $_GET['city'] );
		}
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'get_meteo' ) {
		  print_r($_POST);
		  die();
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

		$output = json_encode($meteo);


		//        echo $GLOBALS['meteo'];
		die($output);
	}
}

$ajax = new Ajax();