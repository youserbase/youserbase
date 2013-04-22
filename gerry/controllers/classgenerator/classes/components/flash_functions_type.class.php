<?php
class flash_functions_type
{

	protected static $instance = null;
	private static $table = 'flash_functions_type';
	public static $data = array();
	public static $flash_functions_type_id;
	public static $flash_functions_type_name;
	public static $timestamp;
	public static $youser_id;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get()
	{
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c;
			self::Init();
		}
		return self::$instance;
	}


	private function Init($flash_functions_type_id = null)
	{
		if($flash_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE flash_functions_type_id = ?;", $flash_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('flash_functions_type_id' => $line['flash_functions_type_id'], 'flash_functions_type_name' => $line['flash_functions_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($flash_functions_type_id = null)
	{
		if($flash_functions_type_id !== null)
		{
			self::Init($flash_functions_type_id);
		}
		return self::$data;
	}

	public function Set($flash_functions_type_id = null, $flash_functions_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($flash_functions_type_id !== null)
		{
			self::$flash_functions_type_id = $flash_functions_type_id;
		}		if($flash_functions_type_name !== null)
		{
			self::$flash_functions_type_name = $flash_functions_type_name;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES flash_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO flash_functions_type (flash_functions_type_id, flash_functions_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE flash_functions_type_id=VALUES(flash_functions_type_id),flash_functions_type_name=VALUES(flash_functions_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->flash_functions_type_id, $this->flash_functions_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>