<?php
class microphone_type
{

	protected static $instance = null;
	private static $table = 'microphone_type';
	public static $data = array();
	public static $microphone_type_id;
	public static $microphone_type_id_int;
	public static $microphone_type_name;
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


	private function Init($microphone_type_id = null)
	{
		if($microphone_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE microphone_type_id = ?;", $microphone_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('microphone_type_id' => $line['microphone_type_id'], 'microphone_type_id_int' => $line['microphone_type_id_int'], 'microphone_type_name' => $line['microphone_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($microphone_type_id = null)
	{
		if($microphone_type_id !== null)
		{
			self::Init($microphone_type_id);
		}
		return self::$data;
	}

	public function Set($microphone_type_id = null, $microphone_type_id_int = null, $microphone_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($microphone_type_id !== null)
		{
			self::$microphone_type_id = $microphone_type_id;
		}		if($microphone_type_id_int !== null)
		{
			self::$microphone_type_id_int = $microphone_type_id_int;
		}		if($microphone_type_name !== null)
		{
			self::$microphone_type_name = $microphone_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES microphone_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO microphone_type (microphone_type_id, microphone_type_id_int, microphone_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE microphone_type_id=VALUES(microphone_type_id),microphone_type_id_int=VALUES(microphone_type_id_int),microphone_type_name=VALUES(microphone_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->microphone_type_id, $this->microphone_type_id_int, $this->microphone_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>