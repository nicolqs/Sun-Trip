<?php

class Ajax {
	public function __construct() {
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'get_meteo' ) {
			$this->getAllWeather();
		}
	}

	protected function getAllWeather() {
		// $meteoApi = Meteo::getInstance();
		// $meteo = $meteoApi->get_global_data();
  //       $meteo = $meteoApi->getMeteo();
  //       $forecast = $meteoApi->getForecast();

        // $output = json_encode( array( $meteo, $forecast ) );


        echo $GLOBALS['meteo'];
        die();
	}
}

$ajax = new Ajax();