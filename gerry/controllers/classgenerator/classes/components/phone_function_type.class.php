<?php
class phone_function_type
{

	protected static $instance = null;
	private static $table = 'phone_function_type';
	public static $data = array();
	public static $phone_function_type_id;
	public static $phone_function_type_id_int;
	public static $phone_function_type_name;
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


	private function Init($phone_function_type_id = null)
	{
		if($phone_function_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE phone_function_type_id = ?;", $phone_function_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('phone_function_type_id' => $line['phone_function_type_id'], 'phone_function_type_id_int' => $line['phone_function_type_id_int'], 'phone_function_type_name' => $line['phone_function_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($phone_function_type_id = null)
	{
		if($phone_function_type_id !== null)
		{
			self::Init($phone_function_type_id);
		}
		return self::$data;
	}

	public function Set($phone_function_type_id = null, $phone_function_type_id_int = null, $phone_function_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($phone_function_type_id !== null)
		{
			self::$phone_function_type_id = $phone_function_type_id;
		}		if($phone_function_type_id_int !== null)
		{
			self::$phone_function_type_id_int = $phone_function_type_id_int;
		}		if($phone_function_type_name !== null)
		{
			self::$phone_function_type_name = $phone_function_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES phone_function_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO phone_function_type (phone_function_type_id, phone_function_type_id_int, phone_function_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE phone_function_type_id=VALUES(phone_function_type_id),phone_function_type_id_int=VALUES(phone_function_type_id_int),phone_function_type_name=VALUES(phone_function_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->phone_function_type_id, $this->phone_function_type_id_int, $this->phone_function_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>