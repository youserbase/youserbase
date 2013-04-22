<?php
class processor_type
{

	protected static $instance = null;
	private static $table = 'processor_type';
	public static $data = array();
	public static $processor_type_id;
	public static $processor_type_id_int;
	public static $processor_type_name;
	public static $manufacturer_id;
	public static $device_type_name;
	public static $timestamp;
	public static $youser_id;


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

	private function Init($processor_type_id = null, $device_type = null)
	{
		if($processor_type_id !== null  && $device_type !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE processor_type_id = ? AND device_type_name = ?;", $processor_type_id, $device_type)->to_array();
			self::$data = array();
		}
		else if($processor_type_id === null  && $device_type !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE device_type_name = ?;", $device_type)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('processor_type_id' => $line['processor_type_id'], 'processor_type_id_int' => $line['processor_type_id_int'], 'processor_type_name' => $line['processor_type_name'], 'device_type_name' => $line['device_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($processor_type_id = null, $device_type = null)
	{
		if($processor_type_id !== null)
		{
			self::Init($processor_type_id, $device_type);
		}
		self::Init($processor_type_id, $device_type);
		return self::$data;
	}

	public function Set($processor_type_id = null, $processor_type_id_int = null, $processor_type_name = null, $manufacturer_id = null, $device_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($processor_type_id !== null)
		{
			self::$processor_type_id = $processor_type_id;
		}		if($processor_type_id_int !== null)
		{
			self::$processor_type_id_int = $processor_type_id_int;
		}		if($processor_type_name !== null)
		{
			self::$processor_type_name = $processor_type_name;
		}		if($manufacturer_id !== null)
		{
			self::$manufacturer_id = $manufacturer_id;
		}		if($device_type_name !== null)
		{
			self::$device_type_name = $device_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES processor_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO processor_type (processor_type_id, processor_type_id_int, processor_type_name, device_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE processor_type_id=VALUES(processor_type_id),processor_type_id_int=VALUES(processor_type_id_int),processor_type_name=VALUES(processor_type_name),device_type_name=VALUES(device_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->processor_type_id, $this->processor_type_id_int, $this->processor_type_name,  $this->device_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>