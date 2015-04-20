<?php

namespace rPeer\Providers;

use rPeer\PersistentRegistryProvider;
use \CACHE;

/**
 *
 * @author acgrid
 *        
 */
class NPHPMemcacheRegistry implements PersistentRegistryProvider {
	
	/**
	 * Resource handle
	 * 
	 * @var CACHE
	 */
	protected $_memcache;
	/**
	 * Adapter to a known Memcache or its child-classes
	 * 
	 * @param Memcache $memcache
	 */
	function __construct(CACHE $memcache) {
		$this->_memcache = $memcache;
	}
	/**
	 * 
	 * @return CACHE
	 */
	function getHandle()
	{
		return $this->_memcache; 
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::clear()
	 *
	 */
	public function clear($key) {
		return $this->_memcache->delete_value($key);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::fetch()
	 *
	 */
	public function fetch($key) {
		return $this->_memcache->get_value($key);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::store()
	 *
	 */
	public function store($key, $value, $expire = 0) {
		return $this->_memcache->cache_value($key, $value, $expire);
	}
}

?>