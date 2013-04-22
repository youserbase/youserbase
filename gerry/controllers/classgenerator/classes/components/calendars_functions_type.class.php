<?php
class calendars_functions_type
{

	protected static $instance = null;
	private static $table = 'calendars_functions_type';
	public static $data = array();
	public static $calendars_functions_type_id;
	public static $calendars_functions_type_id_int;
	public static $calendars_functions_type_name;
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


	private function Init($calendars_functions_type_id = null)
	{
		if($calendars_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE calendars_functions_type_id = ?;", $calendars_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('calendars_functions_type_id' => $line['calendars_functions_type_id'], 'calendars_functions_type_id_int' => $line['calendars_functions_type_id_int'], 'calendars_functions_type_name' => $line['calendars_functions_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($calendars_functions_type_id = null)
	{
		if($calendars_functions_type_id !== null)
		{
			self::Init($calendars_functions_type_id);
		}
		return self::$data;
	}

	public function Set($calendars_functions_type_id = null, $calendars_functions_type_id_int = null, $calendars_functions_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($calendars_functions_type_id !== null)
		{
			self::$calendars_functions_type_id = $calendars_functions_type_id;
		}		if($calendars_functions_type_id_int !== null)
		{
			self::$calendars_functions_type_id_int = $calendars_functions_type_id_int;
		}		if($calendars_functions_type_name !== null)
		{
			self::$calendars_functions_type_name = $calendars_functions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES calendars_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO calendars_functions_type (calendars_functions_type_id, calendars_functions_type_id_int, calendars_functions_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE calendars_functions_type_id=VALUES(calendars_functions_type_id),calendars_functions_type_id_int=VALUES(calendars_functions_type_id_int),calendars_functions_type_name=VALUES(calendars_functions_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->calendars_functions_type_id, $this->calendars_functions_type_id_int, $this->calendars_functions_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>