<?php

require_once('const.php');
require_once('lib/yml/spyc.php');
require_once('json.php');
require_once('lib/cache.php');
require_once('lib/meteo.php');
require_once('lib/ajax.php');

$m = Meteo::getInstance();


$res = $m->get_filtered_data('0913', '0921', 50, 80, false);
$res = $m->get_filtered_data('0913', '0921', 60, 80, false);
$res = $m->get_filtered_data('0913', '0921', 70, 80, false);
$res = $m->get_filtered_data('0913', '0921', 50, 90, false);
$res = $m->get_filtered_data('0913', '0921', 60, 90, false);
$res = $m->get_filtered_data('0913', '0921', 70, 90, false);
$res = $m->get_filtered_data('0913', '0921', 80, 90, false);
$res = $m->get_filtered_data('0913', '0921', 50, 100, false);
$res = $m->get_filtered_data('0913', '0921', 60, 100, false);
$res = $m->get_filtered_data('0913', '0921', 70, 100, false);
$res = $m->get_filtered_data('0913', '0921', 80, 100, false);

$res = $m->get_filtered_data('0913', '0921', 50, 80, true);
$res = $m->get_filtered_data('0913', '0921', 60, 80, true);
$res = $m->get_filtered_data('0913', '0921', 70, 80, true);
$res = $m->get_filtered_data('0913', '0921', 50, 90, true);
$res = $m->get_filtered_data('0913', '0921', 60, 90, true);
$res = $m->get_filtered_data('0913', '0921', 70, 90, true);
$res = $m->get_filtered_data('0913', '0921', 80, 90, true);
$res = $m->get_filtered_data('0913', '0921', 50, 100, true);
$res = $m->get_filtered_data('0913', '0921', 60, 100, true);
$res = $m->get_filtered_data('0913', '0921', 70, 100, true);
$res = $m->get_filtered_data('0913', '0921', 80, 100, true);


$res = $m->get_filtered_data('0913', '0921', 40, 50, false);
$res = $m->get_filtered_data('0913', '0921', 40, 60, false);
$res = $m->get_filtered_data('0913', '0921', 40, 70, false);
$res = $m->get_filtered_data('0913', '0921', 50, 85, false);
$res = $m->get_filtered_data('0913', '0921', 60, 85, false);
$res = $m->get_filtered_data('0913', '0921', 70, 85, false);
$res = $m->get_filtered_data('0913', '0921', 80, 85, false);

$res = $m->get_filtered_data('0913', '0921', 40, 50, true);
$res = $m->get_filtered_data('0913', '0921', 40, 60, true);
$res = $m->get_filtered_data('0913', '0921', 40, 70, true);
$res = $m->get_filtered_data('0913', '0921', 50, 85, true);
$res = $m->get_filtered_data('0913', '0921', 60, 85, true);
$res = $m->get_filtered_data('0913', '0921', 70, 85, true);
$res = $m->get_filtered_data('0913', '0921', 80, 85, true);
