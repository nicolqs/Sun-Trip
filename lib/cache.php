<?php

class Cache {
  protected $_ip = '88.190.224.121';
  protected $_port = 11211;

  private static $_instance = NULL;
  private $_memcacheObj = NULL;

  private function __construct() {
    $m = new Memcache();
    $m->connect($this->_ip, $this->_port);
    $this->_memcacheObj = $m;
  }

  public static function getInstance() {
    if (self::$_instance != NULL) {
      return self::$_instance;
    }

    self::$_instance = new Cache();
    return self::$_instance;
  }

  public function get($key) {
    return $this->_memcacheObj->get($key);
  }

  public function set($key, $value) {
    return $this->_memcacheObj->set($key, $value);
  }
}
