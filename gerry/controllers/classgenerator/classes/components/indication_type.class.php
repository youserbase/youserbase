<?php
class indication_type
{

	protected static $instance = null;
	private static $table = 'indication_type';
	public static $data = array();
	public static $indication_type_id;
	public static $indication_type_id_int;
	public static $indication_type_name;
	public static $youser_id;
	public static $timestamp;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($device_type = null)
	{
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c;
			self::Init(null, $device_type);
		}
		return self::$instance;
	}


	private function Init($indication_type_id = null, $device_type = null)
	{
		if($indication_type_id !== null && $device_type !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE indication_type_id = ? AND device_type = ?;", $indication_type_id, $device_type)->to_array();
			self::$data = array();
		}
		else if($indication_type_id === null && $device_type !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE device_type = ?;", $device_type)->to_array();
			self::$data = array();
		}
		else{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('indication_type_id' => $line['indication_type_id'], 'indication_type_id_int' => $line['indication_type_id_int'], 'indication_type_name' => $line['indication_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($indication_type_id = null, $device_type = null)
	{
		if($indication_type_id !== null)
		{
			self::Init($indication_type_id, $device_type);
		}
		else self::Init($indication_type_id, $device_type);
		return self::$data;
	}

	public function Set($indication_type_id = null, $indication_type_id_int = null, $indication_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($indication_type_id !== null)
		{
			self::$indication_type_id = $indication_type_id;
		}		if($indication_type_id_int !== null)
		{
			self::$indication_type_id_int = $indication_type_id_int;
		}		if($indication_type_name !== null)
		{
			self::$indication_type_name = $indication_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES indication_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO indication_type (indication_type_id, indication_type_id_int, indication_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE indication_type_id=VALUES(indication_type_id),indication_type_id_int=VALUES(indication_type_id_int),indication_type_name=VALUES(indication_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->indication_type_id, $this->indication_type_id_int, $this->indication_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>