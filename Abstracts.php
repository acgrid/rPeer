<?php

namespace rPeer;
use rPeer\Entities\NativePeer;
use rPeer\Entities\PrivatePeer;
/**
 * Interface protocols
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

interface PrivatePeerChain
{
	public function setNext(PrivatePeerChain $handler);
	public function handle();
}

interface PrivatePeerReader
{
	public function authenicate(NativePeer $peer); // User ID
	public function checkClient(NativePeer $peer); // NULL
	public function lookup(NativePeer $peer); // Torrent ID
	public function select(NativePeer $peer); // *_ts last_* initial_* connectable_* exchangable OR undefined for the first report
	public function checkCheat(NativePeer $peer); 	
	public function checkLimit(NativePeer $peer); 	
	public function checkWait(NativePeer $peer); 	
	public function checkSlot(NativePeer $peer); 	
	public function checkIPv4(NativePeer $peer); // First Only: check connectable_ipv4
	public function checkIPv6(NativePeer $peer); // First Only: check connectable_ipv6
	public function setExchangable(PrivatePeer $peer); // First Only: determine exchangable
}