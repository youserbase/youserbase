<?php
class internal_memory_type
{

	protected static $instance = null;
	private static $table = 'internal_memory_type';
	public static $data = array();
	public static $internal_memory_type_id;
	public static $internal_memory_type_name;
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


	private function Init($internal_memory_type_id = null)
	{
		if($internal_memory_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE internal_memory_type_id = ?;", $internal_memory_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('internal_memory_type_id' => $line['internal_memory_type_id'], 'internal_memory_type_name' => $line['internal_memory_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($internal_memory_type_id = null)
	{
		if($internal_memory_type_id !== null)
		{
			self::Init($internal_memory_type_id);
		}
		return self::$data;
	}

	public function Set($internal_memory_type_id = null, $internal_memory_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($internal_memory_type_id !== null)
		{
			self::$internal_memory_type_id = $internal_memory_type_id;
		}		if($internal_memory_type_name !== null)
		{
			self::$internal_memory_type_name = $internal_memory_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES internal_memory_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO internal_memory_type (internal_memory_type_id, internal_memory_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE internal_memory_type_id=VALUES(internal_memory_type_id),internal_memory_type_name=VALUES(internal_memory_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->internal_memory_type_id, $this->internal_memory_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>