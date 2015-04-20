<?php

namespace rPeer\Providers;

use rPeer\PeerDatabaseProvider;
use rPeer\Entities\PrivatePeer;

/**
 * General MySQL Peer database provider
 * A helper (reader) will inform the table and field names or NOT-SUPPORTED
 *        
 */
class MySQLPeerProvider implements PeerDatabaseProvider {

	const CONFIG_HOST = 'mysql_host';
	const CONFIG_PORT = 'mysql_port';
	const CONFIG_USER = 'mysql_user';
	const CONFIG_PASS = 'mysql_pass';
	const CONFIG_NAME = 'mysql_name';
	
	/**
	 * 
	 * @var unknown
	 */
	protected $_reader;
	/**
	 */
	function __construct() {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getTorrentPeers()
	 *
	 */
	public function getTorrentPeers($torrentid, $seeding = true, $leeching = true) {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getNativeConnection()
	 *
	 */
	public function getNativeConnection() {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getSeedersCount()
	 *
	 */
	public function getSeedersCount($torrentid) {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getUserPeers()
	 *
	 */
	public function getUserPeers($userid, $torrentid = 0, $seeding = true, $leeching = true) {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getPeerByID()
	 *
	 */
	public function getPeerByID($id) {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getPeerList()
	 *
	 */
	public function getPeerList($torrentid, $pex = true) {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::persist()
	 *
	 */
	public function persist(PrivatePeer $peer) {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getLeechersCount()
	 *
	 */
	public function getLeechersCount($torrentid) {
	}

	/**
	 * (non-PHPdoc)
	 * 
	 * @see \rPeer\PeerDatabaseProvider::getPeerByRaw()
	 *
	 */
	public function getPeerByRaw($peerid, $infohash) {
	}
}
