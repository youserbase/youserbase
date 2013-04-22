<?php
class resolution_units_type
{

	protected static $instance = null;
	private static $table = 'resolution_units_type';
	public static $data = array();
	public static $resolution_units_type_id;
	public static $resolution_units_type_name;
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


	private function Init($resolution_units_type_id = null)
	{
		if($resolution_units_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE resolution_units_type_id = ?;", $resolution_units_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('resolution_units_type_id' => $line['resolution_units_type_id'], 'resolution_units_type_name' => $line['resolution_units_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($resolution_units_type_id = null)
	{
		if($resolution_units_type_id !== null)
		{
			self::Init($resolution_units_type_id);
		}
		return self::$data;
	}

	public function Set($resolution_units_type_id = null, $resolution_units_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($resolution_units_type_id !== null)
		{
			self::$resolution_units_type_id = $resolution_units_type_id;
		}		if($resolution_units_type_name !== null)
		{
			self::$resolution_units_type_name = $resolution_units_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES resolution_units_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO resolution_units_type (resolution_units_type_id, resolution_units_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE resolution_units_type_id=VALUES(resolution_units_type_id),resolution_units_type_name=VALUES(resolution_units_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->resolution_units_type_id, $this->resolution_units_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>