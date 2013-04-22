<?php
class message_function_type
{

	protected static $instance = null;
	private static $table = 'message_function_type';
	public static $data = array();
	public static $message_function_type_id;
	public static $message_function_type_id_int;
	public static $message_function_type_name;
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


	private function Init($message_function_type_id = null)
	{
		if($message_function_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE message_function_type_id = ?;", $message_function_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('message_function_type_id' => $line['message_function_type_id'], 'message_function_type_id_int' => $line['message_function_type_id_int'], 'message_function_type_name' => $line['message_function_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($message_function_type_id = null)
	{
		if($message_function_type_id !== null)
		{
			self::Init($message_function_type_id);
		}
		return self::$data;
	}

	public function Set($message_function_type_id = null, $message_function_type_id_int = null, $message_function_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($message_function_type_id !== null)
		{
			self::$message_function_type_id = $message_function_type_id;
		}		if($message_function_type_id_int !== null)
		{
			self::$message_function_type_id_int = $message_function_type_id_int;
		}		if($message_function_type_name !== null)
		{
			self::$message_function_type_name = $message_function_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES message_function_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO message_function_type (message_function_type_id, message_function_type_id_int, message_function_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE message_function_type_id=VALUES(message_function_type_id),message_function_type_id_int=VALUES(message_function_type_id_int),message_function_type_name=VALUES(message_function_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->message_function_type_id, $this->message_function_type_id_int, $this->message_function_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>