<?php
class additional_hardware_type
{

	protected static $instance = null;
	private static $table = 'additional_hardware_type';
	public static $data = array();
	public static $additional_hardware_type_id;
	public static $additional_hardware_type_id_int;
	public static $additional_hardware_type_name;
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


	private function Init($additional_hardware_type_id = null)
	{
		if($additional_hardware_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE additional_hardware_type_id = ?;", $additional_hardware_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('additional_hardware_type_id' => $line['additional_hardware_type_id'], 'additional_hardware_type_id_int' => $line['additional_hardware_type_id_int'], 'additional_hardware_type_name' => $line['additional_hardware_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($additional_hardware_type_id = null)
	{
		if($additional_hardware_type_id !== null)
		{
			self::Init($additional_hardware_type_id);
		}
		return self::$data;
	}

	public function Set($additional_hardware_type_id = null, $additional_hardware_type_id_int = null, $additional_hardware_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($additional_hardware_type_id !== null)
		{
			self::$additional_hardware_type_id = $additional_hardware_type_id;
		}		if($additional_hardware_type_id_int !== null)
		{
			self::$additional_hardware_type_id_int = $additional_hardware_type_id_int;
		}		if($additional_hardware_type_name !== null)
		{
			self::$additional_hardware_type_name = $additional_hardware_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES additional_hardware_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO additional_hardware_type (additional_hardware_type_id, additional_hardware_type_id_int, additional_hardware_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE additional_hardware_type_id=VALUES(additional_hardware_type_id),additional_hardware_type_id_int=VALUES(additional_hardware_type_id_int),additional_hardware_type_name=VALUES(additional_hardware_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->additional_hardware_type_id, $this->additional_hardware_type_id_int, $this->additional_hardware_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>