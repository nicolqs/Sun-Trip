<?php

include 'meteo.php';

class Ajax {
	public function __construct() {
		if ( isset( $_POST['action'] ) && $_POST['action'] == 'get_meteo' ) {
			$this->getAllWeather();
		}
	}

	protected function getAllWeather() {
		$meteoApi = Meteo::getInstance();
        $meteo = $meteoApi->getMeteo();
        $forecast = $meteoApi->getForecast();

        // $output = json_encode( array( $meteo, $forecast ) );

        echo $meteo;
        die();
	}
}

$ajax = new Ajax();