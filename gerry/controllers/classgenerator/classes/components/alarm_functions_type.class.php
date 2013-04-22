<?php
class alarm_functions_type
{

	protected static $instance = null;
	private static $table = 'alarm_functions_type';
	public static $data = array();
	public static $alarm_functions_type_id;
	public static $alarm_functions_type_id_int;
	public static $alarm_functions_type_name;
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


	private function Init($alarm_functions_type_id = null)
	{
		if($alarm_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE alarm_functions_type_id = ?;", $alarm_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('alarm_functions_type_id' => $line['alarm_functions_type_id'], 'alarm_functions_type_id_int' => $line['alarm_functions_type_id_int'], 'alarm_functions_type_name' => $line['alarm_functions_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($alarm_functions_type_id = null)
	{
		if($alarm_functions_type_id !== null)
		{
			self::Init($alarm_functions_type_id);
		}
		return self::$data;
	}

	public function Set($alarm_functions_type_id = null, $alarm_functions_type_id_int = null, $alarm_functions_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($alarm_functions_type_id !== null)
		{
			self::$alarm_functions_type_id = $alarm_functions_type_id;
		}		if($alarm_functions_type_id_int !== null)
		{
			self::$alarm_functions_type_id_int = $alarm_functions_type_id_int;
		}		if($alarm_functions_type_name !== null)
		{
			self::$alarm_functions_type_name = $alarm_functions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES alarm_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO alarm_functions_type (alarm_functions_type_id, alarm_functions_type_id_int, alarm_functions_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE alarm_functions_type_id=VALUES(alarm_functions_type_id),alarm_functions_type_id_int=VALUES(alarm_functions_type_id_int),alarm_functions_type_name=VALUES(alarm_functions_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->alarm_functions_type_id, $this->alarm_functions_type_id_int, $this->alarm_functions_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>