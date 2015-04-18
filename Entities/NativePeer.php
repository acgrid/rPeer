<?php

namespace rPeer\Entities;
use \SplObjectStorage;
use rPeer\Utiltiy;
use rPeer\DataFilter;
use rPeer\NativePeerReader;
use rPeer\PeerIDLengthException;
use rPeer\InfoHashLengthException;
use rPeer\IPInvalidException;
use rPeer\PortInvalidException;
use rPeer\NegativeValueException;
use rPeer\TrackerIDException;

/**
 * An application-independent BitTorrent peer.
 * Private trackers may need something special from the params, like passkey. 
 * Let the authenicator check about it 
 */
class NativePeer
{
	const FIELD_AGENT = 'user-agent';
	const FIELD_PEER_ID = 'peer-id';
	const FIELD_INFO_HASH = 'info-hash';
	const FIELD_DIRECT_IP = 'direct-ip';
	const FIELD_REPORT_IPV4 = 'report-ipv4';
	const FIELD_REPORT_IPV6 = 'report-ipv6';
	const FIELD_PROXIED_IP = 'proxied-ip';
	const FIELD_PORT = 'port';
	const FIELD_UPLOADED = 'uploaded';
	const FIELD_DOWNLOADED = 'downloaded';
	const FIELD_LEFT = 'left';
	const FIELD_COMPACT = 'compact';
	const FIELD_NO_PEER_ID = 'no-peer-id';
	const FIELD_EVENT = 'event';
	const FIELD_NUM_WANT = 'num-want';
	const FIELD_KEY = 'key';
	const FIELD_TRACKER_ID = 'tracker-id';
	
	const EMPTY_USERAGENT = 'User-Agent not set';
	const SHA1_DIGEST_HEX = 20;
	const PROXY_ANONYMOUS = 'Hidden';
	const DEFAULT_NUMWANT = 50;
	const EVENT_STARTED = 'started';
	const EVENT_STOPPED = 'stopped';
	const EVENT_COMPLETED = 'completed';
	/**
	 * Reference to the request params like $_GET
	 * @var array
	 */
	protected $_params;
	/**
	 * Reference to the environment like $_SERVER
	 * @var array
	 */
	protected $_server;
	/**
	 * A reader instance responsible to interprate my params and environments 
	 * @var NativePeerReader
	 */
	protected $_reader;
	/**
	 * Extra filters to apply special rule on the params
	 * @var array
	 */
	protected $filters = array();
	/**
	 * User Agent string
	 * @var string
	 */
	protected $agent;
	/**
	 * Cached peer_id param
	 * @var string
	 */
	protected $peer_id;
	/**
	 * Cached info_hash param
	 * Raw (urldecoded) 20-byte SHA1 hash of the value of the info key from the Metainfo file. 
	 * @var string 
	 */
	protected $info_hash;
	/**
	 * Server-reported IP, usually from $_SERVER['REMOTE_ADDR']
	 * @var string
	 */
	protected $direct_ip;
	/**
	 * Client-reported IPv4, usually from $_GET['ip']
	 * @var string or NULL
	 */
	protected $report_ipv4;
	/**
	 * Client-reported IPv6, usually from $_GET['ipv6']
	 * @var string or NULL
	 */
	protected $report_ipv6;
	/**
	 * Proxy-reported IP, usually from $_SERVER['X_HTTP_FORWARDED_FOR'] etc.
	 * @var string or NULL
	 */
	protected $proxied_ip;
	/**
	 * Cached 'port' param
	 * @var integer
	 */
	protected $port;
	/**
	 * Cached 'uploaded' param
	 * @var integer
	 */
	protected $uploaded;
	/**
	 * Cached 'downloaded' param
	 * @var integer
	 */
	protected $downloaded;
	/**
	 * Cached 'left' param
	 * @var integer
	 */
	protected $left;
	/**
	 * Setting this to 1 indicates that the client accepts a compact response.
     * The peers list is replaced by a peers string with 6 bytes per peer. 
     * The first four bytes are the host (in network byte order), the last two bytes are the port (again in network byte order). 
     * It should be noted that some trackers only support compact responses (for saving bandwidth) and 
     * either refuse requests without "compact=1" or 
     * simply send a compact response unless the request contains "compact=0" (in which case they will refuse the request.)
     * @see https://wiki.theory.org/BitTorrentSpecification
	 * @var boolean
	 */
	protected $compact;
	/**
	 * Indicates that the tracker can omit peer id field in peers dictionary. This option is ignored if compact is enabled.
	 * @var boolean
	 */
	protected $no_peer_id;
	/**
	 * If specified, must be one of started, completed, stopped, (or empty which is the same as not being specified). 
	 * If not specified, then this request is one performed at regular intervals.
	 * @var string
	 */
	protected $event;
	/**
	 * Optional. Number of peers that the client would like to receive from the tracker. 
	 * This value is permitted to be zero. If omitted, typically defaults to 50 peers.
	 * @var integer
	 */
	protected $numwant;
	/**
	 * Optional. An additional client identification mechanism that is not shared with any peers. 
	 * It is intended to allow a client to prove their identity should their IP address change.
	 * @var string
	 */
	protected $key;
	/**
	 * My tracker ID expected, refuse if peer did not provide identical ID
	 * Optional. If a previous announce contained a tracker id, it should be set here.
	 * @var string
	 */
	protected $trackerid;
	/**
	 * Create a native peer with custom rule to be interpated
	 * @param NativePeerReader $reader
	 */
	public function __construct(NativePeerReader $reader)
	{
		$this->_reader = $reader;
	}
	/**
	 * Magic method to access validated and cached param
	 * @param string $property
	 */
	public function __get($property)
	{
		return isset($this->$property) ? $this->$property : null;
	}
	/**
	 * Add a filter about specified field
	 * 
	 * @param string $field
	 * @param DataFilter $filter
	 */
	public function addFilter($field, DataFilter $filter)
	{
		if(!isset($this->filters[$field])) $this->filters[$field] = new SplObjectStorage();
		$this->filters[$field]->attach($filter);
	}
	/**
	 * Apply filters on a field
	 * 
	 * @param string $field
	 * @param mixed $data
	 */
	public function filter($field, $data)
	{
		if(isset($this->filters[$field])){
			foreach($this->filters[$field] as $filter) $data = $filter->filter($data);
		}
		return $data;
	}
	/**
	 * Access untouched params for extension
	 * @param string $key
	 * @return Ambigous <NULL, multitype:>
	 */
	public function getParam($key)
	{
		return isset($this->_params[$key]) ? $this->_params[$key] : null; 		
	}
	/**
	 * Access untouched server variables for extension
	 * @param string $key
	 * @return Ambigous <NULL, multitype:>
	 */
	public function getServerVariable($key)
	{
		return isset($this->_server[$key]) ? $this->_server[$key] : null;
	}
	/**
	 * The real runtime data, normally just put $_GET and $_SERVER without any modification
	 * But you have chances to hack the data to cheat me, for example unit tests.
	 * 
	 * @param array $get
	 * @param array $server
	 */
	public function read(array &$get, array &$server)
	{
		$this->_params = $get;
		$this->_server = $server;
		$this->update();
	}
	/**
	 * Fired when data is set or altered
	 */
	protected function update()
	{
		$this->setAgent($this->_reader->readUserAgent($this->_params, $this->_server));
		$this->setPeerID($this->_reader->readPeerID($this->_params, $this->_server));
		$this->setInfoHash($this->_reader->readInfoHash($this->_params, $this->_server));
		$this->setDirectIP($this->_reader->readDirectIP($this->_params, $this->_server));
		$this->setReportedIPv4($this->_reader->readReportedIPv4($this->_params, $this->_server));
		$this->setReportedIPv6($this->_reader->readReportedIPv6($this->_params, $this->_server));
		$this->setProxiedIP($this->_reader->readProxiedIP($this->_params, $this->_server));
		$this->setPort($this->_reader->readPort($this->_params, $this->_server));
		$this->setUploaded($this->_reader->readUploaded($this->_params, $this->_server));
		$this->setDownloaded($this->_reader->readDownloaded($this->_params, $this->_server));
		$this->setLeft($this->_reader->readLeft($this->_params, $this->_server));
		$this->setCompact($this->_reader->readCompact($this->_params, $this->_server));
		$this->setNoPeerID($this->compact ? false : $this->_reader->readNoPeerID($this->_params, $this->_server));
		$this->setEvent($this->_reader->readEvent($this->_params, $this->_server));
		$this->setNumwant($this->_reader->readNumwant($this->_params, $this->_server));
		$this->setKey($this->_reader->readKey($this->_params, $this->_server));
		if($this->event <> self::EVENT_STARTED && strlen($this->trackerid) > 0
				 && $this->trackerid <> ($trackerid = $this->_reader->readTrackerID($this->_params, $this->_server))){
			throw new TrackerIDException($this->trackerid, $trackerid);
		}
	}
	public function setAgent($agent)
	{
		$this->agent = $this->filter(self::FIELD_AGENT, empty($agent) ? self::EMPTY_USERAGENT : $agent);
	}
	public function setPeerID($peer_id)
	{
		if(strlen($peer_id) <> self::SHA1_DIGEST_HEX) throw new PeerIDLengthException($peer_id);
		$this->peer_id = $this->filter(self::FIELD_PEER_ID, $peer_id);
	}
	public function setInfoHash($infohash)
	{
		if(strlen($infohash) <> self::SHA1_DIGEST_HEX) throw new InfoHashLengthException($infohash);
		$this->info_hash = $this->filter(self::FIELD_INFO_HASH, $infohash);
	}
	public function setDirectIP($ip)
	{
		if(!Utiltiy::validateIP($ip)) throw new IPInvalidException($ip);
		$this->direct_ip = $this->filter(self::FIELD_DIRECT_IP, $ip);
	}
	public function setReportedIPv4($ipv4)
	{
		$this->report_ipv4 = $this->filter(self::FIELD_REPORT_IPV4, Utiltiy::validateIPv4($ipv4) ? $ipv4 : null);
	}
	public function setReportedIPv6($ipv6)
	{
		$this->report_ipv6 = $this->filter(self::FIELD_REPORT_IPV6, Utiltiy::validateIPv6($ipv6) ? ipv6 : null);
	}
	public function setProxiedIP($ip)
	{
		$this->proxied_ip = $this->filter(self::FIELD_PROXIED_IP, Utiltiy::validateIP($ip) ? ip : (empty($ip) ? null : PROXY_ANONYMOUS));
	}
	/**
	 * Extended checks like blacklisted port, let reader do
	 * @param integer $port
	 */
	public function setPort($port)
	{
		$port = (int) $port;
		if($port <= 0 || $port > 0xFFFF) throw new PortInvalidException($port);
		$this->port = $this->filter(self::FIELD_PORT, $port);
	}
	public function setUploaded($uploaded)
	{
		$uploaded = (int) $uploaded;
		if($uploaded < 0) throw new NegativeValueException('uploaded', $uploaded);
		$this->uploaded = $this->filter(self::FIELD_UPLOADED, $uploaded);
	}
	public function setDownloaded($downloaded)
	{
		$downloaded = (int) $downloaded;
		if($downloaded < 0) throw new NegativeValueException('downloaded', $downloaded);
		$this->downloaded = $this->filter(self::FIELD_DOWNLOADED, $downloaded);
	}
	public function setLeft($left)
	{
		$left = (int) $left;
		if($left < 0) throw new NegativeValueException('left', $left);
		$this->left = $this->filter(self::FIELD_LEFT, $left);
	}
	public function setCompact($compact)
	{
		$this->compact = (bool) $this->filter(self::FIELD_COMPACT, $compact);
	}
	public function setNoPeerID($no_peer_id)
	{
		$this->no_peer_id = (bool) $this->filter(self::FIELD_NO_PEER_ID, $no_peer_id);
	}
	public function setEvent($event)
	{
		$this->event = $this->filter(self::FIELD_EVENT, (self::EVENT_STARTED == $event || self::EVENT_STOPPED == $event || self::EVENT_COMPLETED == $event) ? $event : '');  		
	}
	public function setNumwant($numwant)
	{
		$numwant = empty($numwant) ? self::DEFAULT_NUMWANT : (int) $numwant;
		if($numwant < 0) throw new NegativeValueException('numwant', $numwant);
		$this->numwant = $this->filter(self::FIELD_NUM_WANT, $numwant);
	}
	public function setKey($key)
	{
		$this->key = $this->filter(self::FIELD_KEY, $key);
	}
}