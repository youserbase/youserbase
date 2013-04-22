<?php
class operatingsystem_type
{

	protected static $instance = null;
	private static $table = 'operatingsystem_type';
	public static $data = array();
	public static $operatingsystem_type_id;
	public static $operatingsystem_type_id_int;
	public static $operatingsystem_type_name;
	public static $operatingsystem_type_version;
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


	private function Init($operatingsystem_type_id = null, $device_type = null)
	{
		self::$data = array();
		if($operatingsystem_type_id !== null && $device_type !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE operatingsystem_type_id = ? AND device_type_name IN ('$device_type');", $operatingsystem_type_id)->to_array();
		}
		else if($operatingsystem_type_id === null && $device_type !== null)
		{
			
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE device_type_name IN ('$device_type');")->to_array();
		}
		else{
			
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";", $device_type)->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('operatingsystem_type_id' => $line['operatingsystem_type_id'], 'operatingsystem_type_id_int' => $line['operatingsystem_type_id_int'], 'operatingsystem_type_name' => $line['operatingsystem_type_name'], 'operatingsystem_type_version' => $line['operatingsystem_type_version'], 'device_type_name' => $line['device_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($operatingsystem_type_id = null, $device_type = null)
	{
		if($operatingsystem_type_id !== null)
		{
			self::Init($operatingsystem_type_id, $device_type);
		}
		self::Init($operatingsystem_type_id, $device_type);
		return self::$data;
	}

	public function Set($operatingsystem_type_id = null, $operatingsystem_type_id_int = null, $operatingsystem_type_name = null, $operatingsystem_type_version = null, $device_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($operatingsystem_type_id !== null)
		{
			self::$operatingsystem_type_id = $operatingsystem_type_id;
		}		if($operatingsystem_type_id_int !== null)
		{
			self::$operatingsystem_type_id_int = $operatingsystem_type_id_int;
		}		if($operatingsystem_type_name !== null)
		{
			self::$operatingsystem_type_name = $operatingsystem_type_name;
		}		if($operatingsystem_type_version !== null)
		{
			self::$operatingsystem_type_version = $operatingsystem_type_version;
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
		DBManager::Get('devices')->query("LOCK TABLES operatingsystem_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO operatingsystem_type (operatingsystem_type_id, operatingsystem_type_id_int, operatingsystem_type_name, operatingsystem_type_version, device_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE operatingsystem_type_id=VALUES(operatingsystem_type_id),operatingsystem_type_id_int=VALUES(operatingsystem_type_id_int),operatingsystem_type_name=VALUES(operatingsystem_type_name),operatingsystem_type_version=VALUES(operatingsystem_type_version),device_type_name=VALUES(device_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->operatingsystem_type_id, $this->operatingsystem_type_id_int, $this->operatingsystem_type_name, $this->operatingsystem_type_version, $this->device_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>