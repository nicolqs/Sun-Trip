<?php

require_once('const.php');
require_once('lib/yml/spyc.php');
require_once('json.php');
require_once('lib/cache.php');
require_once('lib/meteo.php');
require_once('lib/ajax.php');

$m = Meteo::getInstance();
$res = $m->get_filtered_data('1001', '1015', 70, 90, false);
print_r($res);
