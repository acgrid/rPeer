<?php

namespace rPeer\Chains;

use rPeer\PrivatePeerReaderChain;
use rPeer\Entities\PrivatePeer;

class ClientSlot implements PrivatePeerReaderChain {

	public function handle(PrivatePeer $peer) {
	}

	public function setNext(PrivatePeerReaderChain $handler) {
	}
}

?>