<?php

namespace rPeer\Filters;

use rPeer\DataFilter;
use rPeer\Facade;
use rPeer\Entities\NativePeer;

/**
 * Limit the maximum value of "numwant" paramemter
 * BitTorrent client may expect an amount of peers to accept at most.
 * This filter will reduce the limit if client gives a too high amount to save bandwidth
 *
 */
class NumwantLimitFilter implements DataFilter {
	const CONFIG_NUMWANT_LIMIT = 'numwant_limit';
	const DEFAULT_NUMWANT_LIMIT = 200;

	public function filter($numwant) {
		$limit = Facade::getConfiguration()->get(self::CONFIG_NUMWANT_LIMIT, self::DEFAULT_NUMWANT_LIMIT);
		return $numwant > 0 && $numwant < $limit ? $numwant : NativePeer::DEFAULT_NUMWANT;
	}
}
