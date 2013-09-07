<?php
$ip = '54.234.98.140';
$port = 49399;

$memcache_obj = new Memcache;
$memcache_obj->connect($ip, $port);

$memcache_obj->set('db43e0d3aad', 'nicolqs+tcdisrupt@gmail.com');
$v = $memcache_obj->get("db43e0d3aad");

echo "Welcome $v! Your Memcached server is ready to use :)\n";