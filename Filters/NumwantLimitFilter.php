<?php

namespace rPeer\Filters;

use rPeer\DataFilter;
use rPeer\Facade;
use rPeer\ConfigurationProvider;
use rPeer\Entities\NativePeer;

class NumwantLimitFilter implements DataFilter {
	const DEFAULT_NUMWANT_LIMIT = 200;

	public function filter($numwant) {
		$limit = Facade::getConfiguration()->get(ConfigurationProvider::CONFIG_NUMWANT_LIMIT, self::DEFAULT_NUMWANT_LIMIT);
		return $numwant > 0 && $numwant < $limit ? $numwant : NativePeer::DEFAULT_NUMWANT;
	}
}
