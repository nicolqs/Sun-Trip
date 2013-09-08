<?php

require_once('../lib/yml/spyc.php');

$c = file_get_contents('data');
$c = json_decode($c);

$cities = array();
foreach ($c as $cc)
  {    
    $key = $cc->zmw;
    $cities[$key]['latitude'] = $cc->current_observation->observation_location->latitude;
    $cities[$key]['longitude'] = $cc->current_observation->observation_location->longitude;
    $cities[$key]['dataset'] = $cc->dataset;    
    $cities[$key]['zmw'] = $cc->zmw;    
  }

$yaml_cities = file_get_contents('cities.yml');
$c = Spyc::YAMLLoad($yaml_cities);

$out = '';
foreach ($c as $k => $v)
  {
    $out .= $k . ":\n";
    $out .= '  name: ' . $k . "\n";
    foreach ($v as $kk => $vv)
      {
	if ($kk == 'zmw') $zmw = $vv;
	$out .= "  $kk: $vv\n";
      }
    $out .= "  latitude: " . $cities[$vv]['latitude'] . "\n";
    $out .= "  longitude: " . $cities[$vv]['longitude'] . "\n";
  }
echo $out;