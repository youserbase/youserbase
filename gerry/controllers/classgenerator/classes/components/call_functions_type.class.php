<?php
class call_functions_type
{

	protected static $instance = null;
	private static $table = 'call_functions_type';
	public static $data = array();
	public static $call_functions_type_id;
	public static $call_functions_type_id_int;
	public static $call_functions_type_name;
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


	private function Init($call_functions_type_id = null)
	{
		if($call_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE call_functions_type_id = ?;", $call_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('call_functions_type_id' => $line['call_functions_type_id'], 'call_functions_type_id_int' => $line['call_functions_type_id_int'], 'call_functions_type_name' => $line['call_functions_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($call_functions_type_id = null)
	{
		if($call_functions_type_id !== null)
		{
			self::Init($call_functions_type_id);
		}
		return self::$data;
	}

	public function Set($call_functions_type_id = null, $call_functions_type_id_int = null, $call_functions_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($call_functions_type_id !== null)
		{
			self::$call_functions_type_id = $call_functions_type_id;
		}		if($call_functions_type_id_int !== null)
		{
			self::$call_functions_type_id_int = $call_functions_type_id_int;
		}		if($call_functions_type_name !== null)
		{
			self::$call_functions_type_name = $call_functions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES call_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO call_functions_type (call_functions_type_id, call_functions_type_id_int, call_functions_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE call_functions_type_id=VALUES(call_functions_type_id),call_functions_type_id_int=VALUES(call_functions_type_id_int),call_functions_type_name=VALUES(call_functions_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->call_functions_type_id, $this->call_functions_type_id_int, $this->call_functions_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>