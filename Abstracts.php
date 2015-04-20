<?php
/**
 * 
 * @author acgrid
 */
namespace rPeer;
//use rPeer\Entities\NativePeer;
use rPeer\Entities\PrivatePeer;
/**
 * Interface protocols
 */

/**
 * Provide extra validation or protection on certain type of data
 *
 */
interface DataFilter
{
	public function filter($data);
}

/**
 * The protocol about NativePeer and its reader
 * A NativePeerReader must implement how to determine each field from provided GET params and SERVER variable
 * For consistency, all methods accepts the same arguments signature, even it's maybe superfluous 
 * To reduce copy cost, always call by reference 
 *
 */
interface NativePeerReader
{
	public function readUserAgent(array &$get, array &$server);
	public function readPeerID(array &$get, array &$server);
	public function readInfoHash(array &$get, array &$server);
	public function readDirectIP(array &$get, array &$server);
	public function readReportedIPv4(array &$get, array &$server);
	public function readReportedIPv6(array &$get, array &$server);
	public function readProxiedIP(array &$get, array &$server);
	public function readPort(array &$get, array &$server);
	public function readUploaded(array &$get, array &$server);
	public function readDownloaded(array &$get, array &$server);
	public function readLeft(array &$get, array &$server);
	public function readCompact(array &$get, array &$server);
	public function readNoPeerID(array &$get, array &$server);
	public function readEvent(array &$get, array &$server);
	public function readNumwant(array &$get, array &$server);
	public function readKey(array &$get, array &$server);
	public function readTrackerID(array &$get, array &$server);
}
/**
 * Chains of responsibility conerned on PrivatePeer processing
 * Typically two phases to go:
 * Construction/Resume of PrivatePeer with many checking and validation
 * Additional checks before persistentation processing
 *
 */
interface PrivatePeerReaderChain
{
	public function setNext(PrivatePeerReaderChain $handler);
	public function handle(PrivatePeer $peer);
}

/**
 * Logger Service for tracker
 *
 */
interface Logger
{
	const LEVEL_DUMP = 1;
	const LEVEL_DEBUG = 2;
	const LEVEL_NOTICE = 4;
	const LEVEL_WARNING = 8;
	const LEVEL_ERROR = 16;
	const LEVEL_CRITICAL = 32;
	public function dump(array $get, array $server);
	public function debug($line);
	public function notice($line);
	public function warning($line);
	public function error($line);
	public function critical($line);
}

/**
 * Error reporting for tracker
 *
 */
interface ErrorHandler
{
	public function halt($message);
	public function exception(\Exception $e);
}

interface ConfigurationReader
{
	public function map($key);
}

interface ConfigurationProvider
{
	public function get($key, $default = NULL);
	public function set($key, $value);
}

interface RegistryProvider
{
	public function has($key);
	public function get($key);
	public function set($key, $value);
	public function del($key); 
}

interface PersistentRegistryProvider
{
	public function store($key, $value, $expire = 0);
	public function fetch($key);
	public function clear($key);
}

/**
 * Elementary interface for database-independent peer database interactions 
 * Note that joined and extended queries need more complicated operations which provided by special classes
 */
interface PeerDatabaseProvider
{
	/**
	 * Return the native connection handle for raw API access
	 */
	public function getNativeConnection();
	
	public function getPeerList($torrentid, $pex = true);
	
	public function getPeerByID($id);
	
	public function getPeerByRaw($peerid, $infohash);
	
	public function getUserPeers($userid, $torrentid = 0, $seeding = true, $leeching = true);
	
	public function getTorrentPeers($torrentid, $seeding = true, $leeching = true);
	
	public function getSeedersCount($torrentid);
	
	public function getLeechersCount($torrentid);
	/**
	 * Make a full qualified PrivatePeer persisted
	 * 
	 * @param PrivatePeer $peer
	 */
	public function persist(PrivatePeer $peer);
}
/**
 * Additional interface for RDBMS providers
 * Assure helpers can do custom queries or special setup on the connections
 *
 */
interface RDBMSProvider
{
	public function getHandle();
	public function query($sql);
}
/**
 * Handle the variations about initialization, names and custom features 
 *
 */
interface RDBMSReader
{
	/**
	 * Custom operations immediately after connected to DB
	 * 
	 * @param RDBMSProvider $db
	 */
	public function initialize(RDBMSProvider $db);
	/**
	 * Get local table name
	 * 
	 * @param string $name
	 * @return string
	 */
	public function table($name = 'peers');
	/**
	 * Get local field name
	 * 
	 * @param string $name
	 * @return string
	 */
	public function field($name);
	/**
	 * Get local fields list for insert
	 * 
	 * @param string $table
	 * @return array
	 */
	public function fields($table = 'peers');
	/**
	 * Get the callback used to persist the field in RAW SQL 
	 * 
	 * @param PrivatePeer $peer
	 * @return array (field => escaped_string_to_insert_update)
	 */
	public function persist(PrivatePeer $peer);
	
}