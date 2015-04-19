<?php

namespace rPeer\Providers;

use rPeer\ConfigurationProvider;
use rPeer\ConfigurationException;

class NexusPHPConfiguration implements ConfigurationProvider {

	function __construct($bootstrap = null) {
		if(!function_exists('\ReadConfig')) throw new ConfigurationException('Configuration context does not exist.');
	}
	
	protected function map($key)
	{
		switch($key){
			case self::CONFIG_LOG_FILE: return 'announce_log_tweak';
			default: return $key;
		}
	}

	public function get($key, $default = NULL) {
		$key = $this->map($key);
		return isset($GLOBALS[$key]) ? $GLOBALS[$key] : $default;
	}

	public function set($key, $value) {
		// Not supported yet
	}
}

?>