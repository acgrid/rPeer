<?php

namespace rPeer\ErrorHandlers;

use \Exception;
use rPeer\ErrorHandler;
use rPeer\Utiltiy;

/**
 * Output the BitTorrent standard "failure reason" to client
 * @author acgrid
 *        
 */
class BencodeErrorHandler implements ErrorHandler {
	
	protected $verbose = false;
	
	public function __construct($verbose = false)
	{
		$this->verbose = $verbose;
		set_exception_handler(array($this, 'exception'));
	}
	
	/**
	 * Plain Error message output 
	 * @see \rPeer\ErrorHandler::halt()
	 *
	 */
	public function halt($message) {
		Utiltiy::outputPlain('d14:failure reason', strlen($message), ":{$message}e");
		exit;
	}

	/**
	 * Automatic Exception message convertion for verbose output or not
	 *
	 * @see \rPeer\ErrorHandler::exception()
	 *
	 */
	public function exception(Exception $e) {
		$message = $this->verbose ? sprintf('%s "%s" on file %s line %u.', Utiltiy::__('Exception'), $e->getMessage(), $e->getFile(), $e->getLine()) : 
			sprintf('ERR #%s%u', strtoupper(substr(basename($e->getFile()), 1, 3)), $e->getLine()); 
		$this->halt($message);
	}

}

?>