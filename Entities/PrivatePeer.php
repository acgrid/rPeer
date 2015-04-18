<?php

namespace rPeer\Entities;
use rPeer\Utiltiy;
use rPeer\PrivatePeerReader;

/**
 * A standarded peer with private tracker characteristics
 *
 */
class PrivatePeer
{
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
	 * @var PrivatePeerReader
	 */
	protected $_reader;
	/**
	 * The man-made serialized ID, taken by database
	 * This had better be a 64-bit integer
	 * 
	 * @var integer
	 */
	protected $id = 0;
	/**
	 * The user ID of peer owner
	 * Untouched value 0 indicates needing peer primary-key query/insert
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
	
	public function __construct(PrivatePeerReader $reader)
	{
		$this->_reader = $reader;
	}
	public function read(NativePeer $peer)
	{
		$this->_peer = $peer;
	}
}