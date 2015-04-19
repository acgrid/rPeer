<?php

namespace rPeer\Logger;

use rPeer\Logger;

class FileLogger implements Logger
{
	protected $level;
	protected $_handle;
	public function __construct($file, $level = null)
	{
		$this->level = $level === null ? self::LEVEL_WARNING | self::LEVEL_ERROR | self::LEVEL_CRITICAL : $level;
		$this->_handle = fopen(file,'w');
	}
	public function __destruct()
	{
		if($this->_handle) fclose($this->_handle);
	}
	protected function write($line)
	{
		if($this->_handle) fwrite($this->_handle, sprintf("%s - %s\n", date('Y-m-d H:i:s'), $line));
	}
	public function dump(array $get, array $server)
	{
		return; // TODO: Plain Log is not suitable for dump
	}
	public function debug($line)
	{
		if($this->level & self::LEVEL_DEBUG == self::LEVEL_DEBUG) $this->write($line);
	}
	public function notice($line)
	{
		if($this->level & self::LEVEL_NOTICE == self::LEVEL_NOTICE) $this->write($line);
	}
	public function warning($line)
	{
		if($this->level & self::LEVEL_WARNING == self::LEVEL_WARNING) $this->write($line);
	}
	public function error($line){
		if($this->level & self::LEVEL_ERROR == self::LEVEL_ERROR) $this->write($line);
	}
	public function critical($line)
	{
		if($this->level & self::LEVEL_CRITICAL == self::LEVEL_CRITICAL) $this->write($line);
	}
}