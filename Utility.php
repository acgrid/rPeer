<?php
namespace rPeer;

use Exception;

/**
 * 
 * @author acgrid
 *
 */
class Utiltiy
{
	public static function validateIPv4($ip)
	{
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
	}
	public static function validateIPv6($ip)
	{
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
	}
	public static function validateIP($ip)
	{
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
	}
	public static function now()
	{
		if(defined('TIMENOW')) return TIMENOW;
		if(isset($_SERVER['REQUEST_TIME'])) return $_SERVER['REQUEST_TIME'];
		return time();
	}
	/**
	 * Gettext wrapper
	 * Simply return if Gettext is not applied 
	 * 
	 * @param string $text
	 * @return string
	 */
	public static function __($text)
	{
		return function_exists('\__') ? __($text) : $text;
	}
	/**
	 * Gettext wrapper with context support
	 * 
	 * @param string $text
	 * @return string
	 */
	public static function _x($context, $text)
	{
		return function_exists('\_x') ? _x($context, $text) : $text;
	}
	public static function outputPlain($data)
	{
		if(headers_sent()) throw new HeaderSentException();
		header('Content-Type: text/plain; charset=utf-8');
		header('Pragma: no-cache');
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && $_SERVER['HTTP_ACCEPT_ENCODING'] == 'gzip' && function_exists('gzencode')) {
			header('Content-Encoding: gzip');
			echo gzencode($data, 9, FORCE_GZIP);
		}else{
			echo $data;
		}
	}
}
