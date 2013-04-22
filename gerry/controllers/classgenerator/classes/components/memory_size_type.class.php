<?php
class memory_size_type
{

	protected static $instance = null;
	private static $table = 'memory_size_type';
	public static $data = array();
	public static $memory_size_type_id;
	public static $memory_size_type_name;
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


	private function Init($memory_size_type_id = null)
	{
		if($memory_size_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE memory_size_type_id = ?;", $memory_size_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('memory_size_type_id' => $line['memory_size_type_id'], 'memory_size_type_name' => $line['memory_size_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($memory_size_type_id = null)
	{
		if($memory_size_type_id !== null)
		{
			self::Init($memory_size_type_id);
		}
		return self::$data;
	}

	public function Set($memory_size_type_id = null, $memory_size_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($memory_size_type_id !== null)
		{
			self::$memory_size_type_id = $memory_size_type_id;
		}		if($memory_size_type_name !== null)
		{
			self::$memory_size_type_name = $memory_size_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES memory_size_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO memory_size_type (memory_size_type_id, memory_size_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE memory_size_type_id=VALUES(memory_size_type_id),memory_size_type_name=VALUES(memory_size_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->memory_size_type_id, $this->memory_size_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>