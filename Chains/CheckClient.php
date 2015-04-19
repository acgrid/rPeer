<?php

namespace rPeer\Chains;

use rPeer\PrivatePeerReaderChain;
use rPeer\Entities\PrivatePeer;

class CheckClient implements PrivatePeerReaderChain {

	public function handle(PrivatePeer $peer) {
	}

	public function setNext(PrivatePeerReaderChain $handler) {
	}
}

?>