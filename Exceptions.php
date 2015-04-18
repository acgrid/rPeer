<?php

namespace rPeer;
use \Exception;

/**
 * Exception Classes
 * 
 */

class RequiredParamException extends Exception
{
	public $collection;
	public $key;
	public function __construct($key, $collection)
	{
		$this->collection = $collection;
		$this->key = $key;
		parent::__construct(sprintf('Missing required field "%s".', $key));
	}
}

class PeerIDLengthException extends Exception
{
	public $peer_id;
	public function __construct($peer_id)
	{
		$this->peer_id = $peer_id;
		parent::__construct(sprintf('Invalid Peer ID Length: %u.', strlen($peer_id)));
	}
}

class InfoHashLengthException extends Exception
{
	public $info_hash;
	public function __construct($info_hash)
	{
		$this->info_hash = $info_hash;
		parent::__construct(sprintf('Invalid Info Hash Length: %u.', strlen($info_hash)));
	}
}

class IPInvalidException extends Exception
{
	public $ip;
	public function __construct($ip)
	{
		$this->ip = $ip;
		parent::__construct(sprintf('Not an IPv4 or IPv6 address: %s.', $ip));
	}
}

class PortInvalidException extends Exception
{
	public $port;

	public function __construct($port)
	{
		$this->port = $port;
		parent::__construct(sprintf('Not a Port number: %u.', $port));
	}
}

class NegativeValueException extends Exception
{
	public $param;
	public $value;
	
	public function __construct($param, $value)
	{
		$this->param = $param;
		$this->value = $value;
		parent::__construct(sprintf('Paramemter %s expects to be positive, but %d passed.', $param, $value));
	}
}

class TrackerIDException extends Exception
{
	public $expected;
	public $actual;
	
	public function __construct($expected, $actual)
	{
		$this->expected = $expected;
		$this->actual = $actual;
		parent::__construct(sprintf('Tracker ID should be set as %s but "%s" received.', $expected, $actual));
	}
}

class BlacklistedPortException extends Exception
{
	public $port;

	public function __construct($port)
	{
		$this->port = $port;
		parent::__construct(sprintf('Port number %u is listed in blacklist.', $port));
	}
} 