<?php

namespace rPeer\Readers;
use rPeer\NativePeerReader;
use rPeer\RequiredParamException;

class StandardNativePeerReader implements NativePeerReader {
	/**
	 * Internal processing about the params and server variables
	 * Accept an ordered list (array) of keys to try out or just single string as only key
	 * If no default value specified throws, otherwise return default.
	 * Never use FALSE as default value 
	 * 
	 * @param array $collection
	 * @param array or string $key
	 * @param string $default
	 * @throws RequiredParamException
	 * @return mixed
	 */
	protected function read(array &$collection, $key, $default = false)
	{
		if(is_string($key) && isset($collection[$key])){
			return $collection[$key];
		}elseif(is_array($key)){
			foreach($key as $k){
				$data = $this->read($collection, $k, null);
				if($data !== null) return $data;
			}
		}elseif($default === false){
			throw new RequiredParamException($key, $collection);
		}else{
			return $default;
		}
	}
	public function readDownloaded(array &$get, array &$server) {
		return $this->read($get, 'downloaded');
	}

	public function readInfoHash(array &$get, array &$server) {
		return $this->read($get, 'info_hash');
	}

	public function readPeerID(array &$get, array &$server) {
		return $this->read($get, 'peer_id');
	}

	public function readCompact(array &$get, array &$server) {
		return $this->read($get, 'compact', 0);
	}

	public function readLeft(array &$get, array &$server) {
		return $this->read($get, 'left');
	}

	public function readPort(array &$get, array &$server) {
		return $this->read($get, 'port');
	}

	public function readReportedIPv4(array &$get, array &$server) {
		return $this->read($get, 'ip' , '');
	}

	public function readKey(array &$get, array &$server) {
		return $this->read($get, 'ip' , 'key');
	}

	public function readDirectIP(array &$get, array &$server) {
		return $this->read($server, 'REMOTE_ADDR');
	}

	public function readNumwant(array &$get, array &$server) {
		return $this->read($get, array('numwant', 'num want', 'num_want'), '');
	}

	public function readProxiedIP(array &$get, array &$server) {
		return $this->read($server, array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP'), '');
	}

	public function readEvent(array &$get, array &$server) {
		return $this->read($get, 'event' , '');
	}

	public function readTrackerID(array &$get, array &$server) {
		return $this->read($get, 'trackerid' , '');
	}

	public function readUploaded(array &$get, array &$server) {
		return $this->read($get, 'uploaded' , '');
	}

	public function readReportedIPv6(array &$get, array &$server) {
		return $this->read($get, array('ipv6', 'ip') , '');
	}

	public function readUserAgent(array &$get, array &$server) {
		return $this->read($server, 'HTTP_USER_AGENT');
	}

	public function readNoPeerID(array &$get, array &$server) {
		return $this->read($get, 'no_peer_id' , '');
	}
}
