<?php

namespace rPeer\Providers;

use rPeer\ConfigurationProvider;
use rPeer\ConfigurationReader;

/**
 * Legacy configuration store by global variables
 * A helper (mapper/reader) will translate the constant to corresponding variable name
 *
 */
class VariableConfigurationProvider implements ConfigurationProvider {
	/**
	 * 
	 * @var ConfigurationReader
	 */
	protected $_mapper;
	
	public function __construct(ConfigurationReader $mapper) {
		$this->_mapper = $mapper;
	}
	
	public function get($key, $default = NULL) {
		$key = $this->_mapper->map($key);
		return isset($GLOBALS[$key]) ? $GLOBALS[$key] : $default;
	}

	public function set($key, $value) {
		return;
	}
}

?>