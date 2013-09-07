<?php

$yaml_cities_file = 'yaml_test.yml';
$yaml_cities = file_get_contents($yaml_cities_file);
$cities = yaml_parse($yaml_cities);
print_r($cities);
