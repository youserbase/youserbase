<?php
class manufacturer_device_types
{

	protected static $instance = null;
	private static $table = 'manufacturer_device_types';
	public static $data = array();
	public static $manufacturer_id;
	public static $device_type_id;
	public static $device_type_id_int;


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


	private function Init($manufacturer_device_types_id = null)
	{
		if($manufacturer_device_types_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE manufacturer_id = ?;", $manufacturer_device_types_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('manufacturer_id' => $line['manufacturer_id'], 'device_type_id' => $line['device_type_id'], 'device_type_id_int' => $line['device_type_id_int'], );
		}
	}

	public function Load($manufacturer_device_types_id = null)
	{
		if($manufacturer_device_types_id !== null)
		{
			self::Init($manufacturer_device_types_id);
		}
		return self::$data;
	}

	public function Set($manufacturer_id = null, $device_type_id = null, $device_type_id_int = null)
	{
		if($manufacturer_id !== null)
		{
			self::$manufacturer_id = $manufacturer_id;
		}		if($device_type_id !== null)
		{
			self::$device_type_id = $device_type_id;
		}		if($device_type_id_int !== null)
		{
			self::$device_type_id_int = $device_type_id_int;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES manufacturer_device_types WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO manufacturer_device_types (manufacturer_id, device_type_id, device_type_id_int) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE manufacturer_id=VALUES(manufacturer_id),device_type_id=VALUES(device_type_id),device_type_id_int=VALUES(device_type_id_int);", $this->manufacturer_id, $this->device_type_id, $this->device_type_id_int);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>