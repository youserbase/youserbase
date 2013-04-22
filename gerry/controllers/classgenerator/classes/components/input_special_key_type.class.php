<?php
class input_special_key_type
{

	protected static $instance = null;
	private static $table = 'input_special_key_type';
	public static $data = array();
	public static $input_special_key_type_id;
	public static $input_special_key_type_id_int;
	public static $input_special_key_type_name;
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


	private function Init($input_special_key_type_id = null)
	{
		if($input_special_key_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE input_special_key_type_id = ?;", $input_special_key_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('input_special_key_type_id' => $line['input_special_key_type_id'], 'input_special_key_type_id_int' => $line['input_special_key_type_id_int'], 'input_special_key_type_name' => $line['input_special_key_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($input_special_key_type_id = null)
	{
		if($input_special_key_type_id !== null)
		{
			self::Init($input_special_key_type_id);
		}
		return self::$data;
	}

	public function Set($input_special_key_type_id = null, $input_special_key_type_id_int = null, $input_special_key_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($input_special_key_type_id !== null)
		{
			self::$input_special_key_type_id = $input_special_key_type_id;
		}		if($input_special_key_type_id_int !== null)
		{
			self::$input_special_key_type_id_int = $input_special_key_type_id_int;
		}		if($input_special_key_type_name !== null)
		{
			self::$input_special_key_type_name = $input_special_key_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES input_special_key_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO input_special_key_type (input_special_key_type_id, input_special_key_type_id_int, input_special_key_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE input_special_key_type_id=VALUES(input_special_key_type_id),input_special_key_type_id_int=VALUES(input_special_key_type_id_int),input_special_key_type_name=VALUES(input_special_key_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->input_special_key_type_id, $this->input_special_key_type_id_int, $this->input_special_key_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>