<?php
class time_units_type
{

	protected static $instance = null;
	private static $table = 'time_units_type';
	public static $data = array();
	public static $time_units_type_id;
	public static $time_units_type_name;
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


	private function Init($time_units_type_id = null)
	{
		if($time_units_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE time_units_type_id = ?;", $time_units_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('time_units_type_id' => $line['time_units_type_id'], 'time_units_type_name' => $line['time_units_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($time_units_type_id = null)
	{
		if($time_units_type_id !== null)
		{
			self::Init($time_units_type_id);
		}
		return self::$data;
	}

	public function Set($time_units_type_id = null, $time_units_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($time_units_type_id !== null)
		{
			self::$time_units_type_id = $time_units_type_id;
		}		if($time_units_type_name !== null)
		{
			self::$time_units_type_name = $time_units_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES time_units_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO time_units_type (time_units_type_id, time_units_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE time_units_type_id=VALUES(time_units_type_id),time_units_type_name=VALUES(time_units_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->time_units_type_id, $this->time_units_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>