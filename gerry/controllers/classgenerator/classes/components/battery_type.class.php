<?php
class battery_type
{

	protected static $instance = null;
	private static $table = 'battery_type';
	public static $data = array();
	public static $battery_type_id;
	public static $battery_type_name;
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


	private function Init($battery_type_id = null)
	{
		if($battery_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE battery_type_id = ?;", $battery_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('battery_type_id' => $line['battery_type_id'], 'battery_type_name' => $line['battery_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($battery_type_id = null)
	{
		if($battery_type_id !== null)
		{
			self::Init($battery_type_id);
		}
		return self::$data;
	}

	public function Set($battery_type_id = null, $battery_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($battery_type_id !== null)
		{
			self::$battery_type_id = $battery_type_id;
		}		if($battery_type_name !== null)
		{
			self::$battery_type_name = $battery_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES battery_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO battery_type (battery_type_id, battery_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE battery_type_id=VALUES(battery_type_id),battery_type_name=VALUES(battery_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->battery_type_id, $this->battery_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>