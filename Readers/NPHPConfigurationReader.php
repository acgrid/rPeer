<?php

namespace Readers;

use rPeer\ConfigurationReader;
use rPeer\ConfigurationException;

/**
 * Simple mapper between configuration constant to NexusPHP global variables
 * Use with Providers\GlobalVariableConfiguration class
 *        
 */
class NPHPConfigurationReader implements ConfigurationReader {
	
	public function __construct()
	{
		if(!function_exists('\ReadConfig')) throw new ConfigurationException('Configuration context does not exist.');
	}
	/**
	 * @see \rPeer\ConfigurationReader::map()
	 *
	 */
	public function map($key)
	{
		switch($key){
			case \rPeer\Loggers\FileLogger::CONFIG_LOG_FILE: return 'announce_log_tweak';
			case \rPeer\Filters\NumwantLimitFilter::CONFIG_NUMWANT_LIMIT: return 'numwant_tweak';
			case \rPeer\Providers\MySQLPeerProvider::CONFIG_HOST: return 'mysql_host';
			case \rPeer\Providers\MySQLPeerProvider::CONFIG_USER: return 'mysql_user';
			case \rPeer\Providers\MySQLPeerProvider::CONFIG_PASS: return 'mysql_pass';
			case \rPeer\Providers\MySQLPeerProvider::CONFIG_NAME: return 'mysql_db';
			default: return $key;
		}
	}
	
}

?>