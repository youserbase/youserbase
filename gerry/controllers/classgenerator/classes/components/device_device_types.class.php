<?php
class device_device_types
{

	protected static $instance = null;
	private static $table = 'device_device_types';
	public static $data = array();
	public static $ddt_id;
	public static $device_id;
	public static $device_id_int;
	public static $device_type_name;
	public static $main_type;
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


	private function Init($device_device_types_id = null)
	{
		if($device_device_types_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE ddt_id = ?;", $device_device_types_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('ddt_id' => $line['ddt_id'], 'device_id' => $line['device_id'], 'device_id_int' => $line['device_id_int'], 'device_type_name' => $line['device_type_name'], 'main_type' => $line['main_type'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($device_device_types_id = null)
	{
		if($device_device_types_id !== null)
		{
			self::Init($device_device_types_id);
		}
		return self::$data;
	}

	public function Set($ddt_id = null, $device_id = null, $device_id_int = null, $device_type_name = null, $main_type = null, $timestamp = null, $youser_id = null)
	{
		if($ddt_id !== null)
		{
			self::$ddt_id = $ddt_id;
		}		if($device_id !== null)
		{
			self::$device_id = $device_id;
		}		if($device_id_int !== null)
		{
			self::$device_id_int = $device_id_int;
		}		if($device_type_name !== null)
		{
			self::$device_type_name = $device_type_name;
		}		if($main_type !== null)
		{
			self::$main_type = $main_type;
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
		DBManager::Get('devices')->query("LOCK TABLES device_device_types WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO device_device_types (ddt_id, device_id, device_id_int, device_type_name, main_type, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE ddt_id=VALUES(ddt_id),device_id=VALUES(device_id),device_id_int=VALUES(device_id_int),device_type_name=VALUES(device_type_name),main_type=VALUES(main_type),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->ddt_id, $this->device_id, $this->device_id_int, $this->device_type_name, $this->main_type, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>