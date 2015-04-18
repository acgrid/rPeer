<?php

namespace Filters;

use rPeer\DataFilter;
use rPeer\BlacklistedPortException;

class BlacklistedPortFilter implements DataFilter {

	public function filter($port) {
		if($port >= 411 && $port <= 413 || $port >= 6881 && $port <= 6889 || $port == 1214 || $port >= 6346 && $port <= 6347 || $port == 4662 || $port == 6699)
			throw new BlacklistedPortException($port);
		return $port;
	}
}
