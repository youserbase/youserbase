<?php
class extendable_memory_type
{

	protected static $instance = null;
	private static $table = 'extendable_memory_type';
	public static $data = array();
	public static $extendable_memory_type_id;
	public static $extendable_memory_type_name;
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


	private function Init($extendable_memory_type_id = null)
	{
		if($extendable_memory_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE extendable_memory_type_id = ?;", $extendable_memory_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('extendable_memory_type_id' => $line['extendable_memory_type_id'], 'extendable_memory_type_name' => $line['extendable_memory_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($extendable_memory_type_id = null)
	{
		if($extendable_memory_type_id !== null)
		{
			self::Init($extendable_memory_type_id);
		}
		return self::$data;
	}

	public function Set($extendable_memory_type_id = null, $extendable_memory_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($extendable_memory_type_id !== null)
		{
			self::$extendable_memory_type_id = $extendable_memory_type_id;
		}		if($extendable_memory_type_name !== null)
		{
			self::$extendable_memory_type_name = $extendable_memory_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES extendable_memory_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO extendable_memory_type (extendable_memory_type_id, extendable_memory_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE extendable_memory_type_id=VALUES(extendable_memory_type_id),extendable_memory_type_name=VALUES(extendable_memory_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->extendable_memory_type_id, $this->extendable_memory_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>