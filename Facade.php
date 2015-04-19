<?php

namespace rPeer;

/**
 * The library portal is a loader to determine a typical or custom components scheme
 * Choose a typical routine to bypass the autoload especially for the massive announce and scrape access
 * Or just go straight, such as performing highly-personal queries, for maximum flexible utilizing SPL autoload
 */

class Facade
{
	protected static $instance;
	protected $logger;
	protected $registry;
	protected $persistent;
	protected $provider;
	protected function __construct()
	{
		
	}
	/**
	 * Initialization Entries
	 */
	public static function initNexusPHPAnnounce()
	{
		if(self::getInitialized()) return;
	}
	public static function initNexusPHPFrontend()
	{
		if(self::getInitialized()) return;
	}
	public static function initOpenTracker()
	{
		if(self::getInitialized()) return;
	}
	public static function initAutoload()
	{
		if(self::getInitialized()) return;
		self::$instance = new self();
		spl_autoload_register(); // TODO: Default autoloader OK?
	}

	/**
	 * Return the initialized singleton
	 * 
	 * @throws NotInitializedException
	 * @return Facade;
	 */
	public static function getInitialized()
	{
		if(self::$instance === NULL) throw new NotInitializedException();
		return self::$instance;
	}
	/**
	 * @throws NotInitializedException
	 * @return ConfigurationProvider
	 */
	public static function getConfiguration()
	{
		
	}
	public static function getNativePeerReader()
	{
		
	}
	public static function getNativePeer()
	{
		
	}
	public static function getRegistry()
	{
		
	}
	public static function getLogger()
	{
		return self::getInitialized()->logger;
	}
	public static function halt()
	{

	}
}