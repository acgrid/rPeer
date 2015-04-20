<?php

namespace rPeer\Providers;

use rPeer\PersistentRegistryProvider;
use \Memcache;

/**
 *
 * @author acgrid
 *        
 */
class MemcacheRegistry implements PersistentRegistryProvider {
	
	/**
	 * Resource handle
	 * 
	 * @var Memcache
	 */
	protected $_memcache;
	/**
	 * Adapter to a known Memcache or its child-classes
	 * 
	 * @param Memcache $memcache
	 */
	function __construct(Memcache $memcache) {
		$this->_memcache = $memcache;
	}
	/**
	 * 
	 * @return Memcache
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
		return $this->_memcache->delete($key);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::fetch()
	 *
	 */
	public function fetch($key) {
		return $this->_memcache->get($key);
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see \rPeer\PersistentRegisteryProvider::store()
	 *
	 */
	public function store($key, $value, $expire = 0) {
		return $this->_memcache->set($key, $value, null, $expire);
	}
}

?>