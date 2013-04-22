<?php
class projection_type
{

	protected static $instance = null;
	private static $table = 'projection_type';
	public static $data = array();
	public static $projection_type_id;
	public static $projection_type_name;
	public static $youser_id;
	public static $timestamp;


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


	private function Init($projection_type_id = null)
	{
		if($projection_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE projection_type_id = ?;", $projection_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('projection_type_id' => $line['projection_type_id'], 'projection_type_name' => $line['projection_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($projection_type_id = null)
	{
		if($projection_type_id !== null)
		{
			self::Init($projection_type_id);
		}
		return self::$data;
	}

	public function Set($projection_type_id = null, $projection_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($projection_type_id !== null)
		{
			self::$projection_type_id = $projection_type_id;
		}		if($projection_type_name !== null)
		{
			self::$projection_type_name = $projection_type_name;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES projection_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO projection_type (projection_type_id, projection_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE projection_type_id=VALUES(projection_type_id),projection_type_name=VALUES(projection_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->projection_type_id, $this->projection_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>