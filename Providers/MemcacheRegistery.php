<?php

namespace rPeer\Providers;

use rPeer\PersistentRegisteryProvider;

/**
 *
 * @author acgrid
 *        
 */
class MemcacheRegistery extends \CACHE implements PersistentRegisteryProvider {

	/**
	 *
	 * @param string $host
	 * @param integer $port
	 *        	
	 */
	function __construct($host = 'localhost', $port = 11211) {
		parent::__construct($host = 'localhost', $port = 11211);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::clear()
	 *
	 */
	public function clear($key) {
		$this->delete_value($key);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::fetch()
	 *
	 */
	public function fetch($key) {
		$this->get_value($key);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::store()
	 *
	 */
	public function store($key, $value, $expire = 0) {
		$this->cache_value($key, $value, $expire);
	}
}

?>