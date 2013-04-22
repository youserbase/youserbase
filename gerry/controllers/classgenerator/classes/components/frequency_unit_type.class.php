<?php
class frequency_unit_type
{

	protected static $instance = null;
	private static $table = 'frequency_unit_type';
	public static $data = array();
	public static $frequency_unit_type_id;
	public static $frequency_unit_type_id_int;
	public static $frequency_unit_type_name;
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


	private function Init($frequency_unit_type_id = null)
	{
		if($frequency_unit_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE frequency_unit_type_id = ?;", $frequency_unit_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('frequency_unit_type_id' => $line['frequency_unit_type_id'], 'frequency_unit_type_id_int' => $line['frequency_unit_type_id_int'], 'frequency_unit_type_name' => $line['frequency_unit_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($frequency_unit_type_id = null)
	{
		if($frequency_unit_type_id !== null)
		{
			self::Init($frequency_unit_type_id);
		}
		return self::$data;
	}

	public function Set($frequency_unit_type_id = null, $frequency_unit_type_id_int = null, $frequency_unit_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($frequency_unit_type_id !== null)
		{
			self::$frequency_unit_type_id = $frequency_unit_type_id;
		}		if($frequency_unit_type_id_int !== null)
		{
			self::$frequency_unit_type_id_int = $frequency_unit_type_id_int;
		}		if($frequency_unit_type_name !== null)
		{
			self::$frequency_unit_type_name = $frequency_unit_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES frequency_unit_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO frequency_unit_type (frequency_unit_type_id, frequency_unit_type_id_int, frequency_unit_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE frequency_unit_type_id=VALUES(frequency_unit_type_id),frequency_unit_type_id_int=VALUES(frequency_unit_type_id_int),frequency_unit_type_name=VALUES(frequency_unit_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->frequency_unit_type_id, $this->frequency_unit_type_id_int, $this->frequency_unit_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>