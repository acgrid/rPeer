<?php

namespace rPeer\Entities;
use rPeer\Utiltiy;
use rPeer\PrivatePeerReaderChain;
use rPeer\EntityIDException;

/**
 * A standarded peer with private tracker characteristics
 *
 */
class PrivatePeer
{
	const FIELD_START_TS = 'started';
	const FIELD_CURRENT_TS = 'current';
	const FIELD_LAST_TS = 'last';
	const FIELD_PREVIOUS_TS = 'prev';
	const FIELD_FINISHED_TS = 'finished';

	const FIELD_EXCHANGABLE = 'pex';
	const FIELD_CONNECTABLE_IPV4 = 'ipv4conn';
	const FIELD_CONNECTABLE_IPV6 = 'ipv6conn';
	
	const FIELD_LAST_UPLOADED = 'lastup';
	const FIELD_LAST_DOWNLOADED = 'lastdown';
	
	const FIELD_INIT_UPLOADED = 'initup';
	const FIELD_INIT_DOWNLOADED = 'initdown';
	
	/**
	 * Pre-defined extra field for indicating this peer should be voided
	 */
	const EXTEND_FIELD_FORBIDDEN = 'forbidden';
	/**
	 * Reference to its native peer
	 * 
	 * @var NativePeer;
	 */
	protected $_peer;
	/**
	 * Extra params extended by specific site
	 * @var array
	 */
	protected $_extends;
	/**
	 * A reader instance responsible to read associated NativePeer and try to authenicate and fetch data from specific persisent provider  
	 * @var PrivatePeerReaderChain
	 */
	protected $_reader;
	/**
	 * Stores the default values if reader fails to provide certain params
	 * An associative array whose key is the param key constant (shared by basic and extended fields) and value is the default value provided
	 * 
	 * @var array
	 */
	protected $_defaults;
	/**
	 * The man-made serialized ID, taken by database
	 * This had better be a 64-bit integer
	 * 
	 * @var integer
	 */
	protected $id = NULL;
	/**
	 * The user ID of peer owner
	 * Untouched value NULL indicates needing peer primary-key query
	 * Touched value 0 indicates needing a new peer row insertion 
	 *   
	 * @var integer
	 */
	protected $user_id;
	/**
	 * The torrent ID of peer corresponding to
	 *   
	 * @var integer
	 */
	protected $torrent_id;
	/**
	 * The UNIX timestamp when this peer started
	 *
	 * @var integer
	 */
	protected $start_ts;

	/**
	 * The UNIX timestamp when this report receives
	 *
	 * @var integer
	 */
	protected $this_report_ts;

	/**
	 * The UNIX timestamp when last report has received
	 *
	 * @var integer
	 */
	protected $last_report_ts;
	/**
	 * The UNIX timestamp when the previous report before last report has received
	 *
	 * @var integer
	 */
	protected $prev_report_ts;
	/**
	 * The UNIX timestamp when the peer reported completion if it had
	 * It may be NULL or 0 if not reported so 
	 *
	 * @var integer
	 */
	protected $finished_ts;
	/**
	 * Flag about whether this peer can be exchanged to other peers
	 * Usual determined by connectable_ipv4 || connectable_ipv6
	 *
	 * @var boolean
	 */
	protected $exchangable;
	/**
	 * Flag about whether this peer can accept incoming IPv4 TCP connection
	 * 
	 * @var boolean
	 */
	protected $connectable_ipv4;
	/**
	 * Flag about whether this peer can accept incoming IPv6 TCP connection
	 *
	 * @var boolean
	 */
	protected $connectable_ipv6;
	/**
	 * Historic value of uploaded when last reported
	 * @var integer
	 */
	protected $last_uploaded;
	/**
	 * Historic value of downloaded when last reported
	 * @var integer
	 */
	protected $last_downloaded;
	/**
	 * Historic value of uploaded when firstly reported
	 * @var integer
	 */
	protected $initial_uploaded;
	/**
	 * Historic value of uploaded when firstly reported
	 * @var integer
	 */
	protected $initial_downloaded;
	
	public function __construct(PrivatePeerReaderChain $reader, array $defaults = array())
	{
		$this->_reader = $reader;
		if(empty($defaults)){
			$this->_defaults = array(
				self::FIELD_CURRENT_TS => Utiltiy::now(),
				self::FIELD_EXCHANGABLE => false,
			   	self::FIELD_CONNECTABLE_IPV4 => false, 
				self::FIELD_CONNECTABLE_IPV6 => false,
			);
		}else{
			$this->_defaults = $defaults;
		}
	}
	public function read(NativePeer $peer)
	{
		$this->_peer = $peer;
		$this->_reader->handle($this);
	}
	protected function parseParam(array &$src, $key)
	{
		if(isset($src[$key])){
			return $src[$key];
		}elseif(isset($this->_defaults[$key])){
			return $this->_defaults[$key];
		}else{
			return null;
		}
	}
	public function setUserID($userid)
	{
		if(intval($userid) <= 0) throw new EntityIDException('User ID', $userid);
		$this->user_id = (int) $userid;
	}
	public function setTorrentID($torrentid)
	{
		if(intval($torrentid) <= 0) throw new EntityIDException('Torrent ID', $torrentid);
		$this->torrent_id = (int) $torrentid;
	}
	public function resumeSession($peerid, array $params, array $extra = array())
	{
		if(intval(peerid) <= 0) throw new EntityIDException('Peer Database ID', peerid);
		$this->id = (int) $peerid;
		$this->start_ts = $this->parseParam($params, self::FIELD_START_TS); 
		$this->this_report_ts = $this->parseParam($params, self::FIELD_CURRENT_TS); 
		$this->last_report_ts = $this->parseParam($params, self::FIELD_LAST_TS); 
		$this->prev_report_ts = $this->parseParam($params, self::FIELD_PREVIOUS_TS); 
		$this->finished_ts = $this->parseParam($params, self::FIELD_FINISHED_TS); 
		$this->exchangable = $this->parseParam($params, self::FIELD_EXCHANGABLE); 
		$this->connectable_ipv4 = $this->parseParam($params, self::FIELD_CONNECTABLE_IPV4); 
		$this->connectable_ipv6 = $this->parseParam($params, self::FIELD_CONNECTABLE_IPV6); 
		$this->last_uploaded = $this->parseParam($params, self::FIELD_LAST_UPLOADED); 
		$this->last_downloaded = $this->parseParam($params, self::FIELD_LAST_DOWNLOADED); 
		$this->initial_uploaded = $this->parseParam($params, self::FIELD_INIT_UPLOADED); 
		$this->initial_downloaded = $this->parseParam($params, self::FIELD_INIT_DOWNLOADED); 
	}
	public function startSession()
	{
		$this->id = 0;
		$this->start_ts = Utiltiy::now();
		$this->this_report_ts = $this->start_ts;
		$this->finished_ts = $this->prev_report_ts = $this->last_report_ts = null;
		// Exchangable and connectivity check is not needed now
		$this->last_uploaded = $this->initial_uploaded = $this->_peer->uploaded;
		$this->last_downloaded = $this->initial_downloaded = $this->_peer->downloaded;
	}
	public function setExchangable($pex)
	{
		$this->exchangable = (bool) $pex;
	}
	public function setIPv4Connectivity($bool)
	{
		$this->connectable_ipv4 = (bool) $bool;
	}
	public function setIPv6Connectivity($bool)
	{
		$this->connectable_ipv6 = (bool) $bool;
	}
	/**
	 * Tell whether the peer is fully authenticated and allowed for further processing
	 * 
	 * @return boolean
	 */
	public function valid()
	{
		return $this->user_id > 0 && $this->torrent_id > 0 && $this->id !== NULL
		 && (!isset($this->_extends[self::EXTEND_FIELD_FORBIDDEN]) && !$this->_extends[self::EXTEND_FIELD_FORBIDDEN]);
	}
	/**
	 * Tell whether the peer is fully checked and inspected before be able to persist in database
	 * Connectivity check may be useless in some application, generally PEX should meet most scenarios
	 * 
	 * @return boolean
	 */
	public function validForPersistent()
	{
		return $this->valid() && $this->exchangable !== NULL;
	}
	public function lastUploaded()
	{
		return $this->last_uploaded === NULL ? max(0, $this->_peer->uploaded - $this->last_uploaded) : NULL;
	}
	public function lastDownloaded()
	{
		return $this->last_downloaded === NULL ? (max(0, $this->_peer->downloaded - $this->last_downloaded)) : NULL;
	}
	public function sessionUploaded()
	{
		return $this->initial_uploaded === NULL ? max(0, $this->_peer->uploaded - $this->initial_uploaded) : NULL;
	}
	public function sessionDownloaded()
	{
		return $this->initial_downloaded === NULL ? max(0, $this->_peer->downloaded - $this->initial_downloaded) : NULL;
	}
}