<?php

require_once('const.php');
require_once('lib/yml/spyc.php');
require_once('json.php');
require_once('lib/cache.php');
require_once('lib/meteo.php');
require_once('lib/ajax.php');

$m = Meteo::getInstance();


$res = $m->get_filtered_data('1001', '1015', 50, 90, false);
$res = $m->get_filtered_data('1001', '1015', 60, 90, false);
$res = $m->get_filtered_data('1001', '1015', 70, 90, false);
$res = $m->get_filtered_data('1001', '1015', 80, 90, false);
$res = $m->get_filtered_data('1001', '1015', 50, 100, false);
$res = $m->get_filtered_data('1001', '1015', 60, 100, false);
$res = $m->get_filtered_data('1001', '1015', 70, 100, false);
$res = $m->get_filtered_data('1001', '1015', 80, 100, false);

$res = $m->get_filtered_data('1001', '1015', 50, 90, true);
$res = $m->get_filtered_data('1001', '1015', 60, 90, true);
$res = $m->get_filtered_data('1001', '1015', 70, 90, true);
$res = $m->get_filtered_data('1001', '1015', 80, 90, true);
$res = $m->get_filtered_data('1001', '1015', 50, 100, true);
$res = $m->get_filtered_data('1001', '1015', 60, 100, true);
$res = $m->get_filtered_data('1001', '1015', 70, 100, true);
$res = $m->get_filtered_data('1001', '1015', 80, 100, true);
