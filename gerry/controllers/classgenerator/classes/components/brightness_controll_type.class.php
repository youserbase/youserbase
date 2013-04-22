<?php
class brightness_controll_type
{

	protected static $instance = null;
	private static $table = 'brightness_controll_type';
	public static $data = array();
	public static $brightness_controll_type_id;
	public static $brightness_controll_type_id_int;
	public static $brightness_controll_type_name;
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


	private function Init($brightness_controll_type_id = null)
	{
		if($brightness_controll_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE brightness_controll_type_id = ?;", $brightness_controll_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('brightness_controll_type_id' => $line['brightness_controll_type_id'], 'brightness_controll_type_id_int' => $line['brightness_controll_type_id_int'], 'brightness_controll_type_name' => $line['brightness_controll_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($brightness_controll_type_id = null)
	{
		if($brightness_controll_type_id !== null)
		{
			self::Init($brightness_controll_type_id);
		}
		return self::$data;
	}

	public function Set($brightness_controll_type_id = null, $brightness_controll_type_id_int = null, $brightness_controll_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($brightness_controll_type_id !== null)
		{
			self::$brightness_controll_type_id = $brightness_controll_type_id;
		}		if($brightness_controll_type_id_int !== null)
		{
			self::$brightness_controll_type_id_int = $brightness_controll_type_id_int;
		}		if($brightness_controll_type_name !== null)
		{
			self::$brightness_controll_type_name = $brightness_controll_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES brightness_controll_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO brightness_controll_type (brightness_controll_type_id, brightness_controll_type_id_int, brightness_controll_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE brightness_controll_type_id=VALUES(brightness_controll_type_id),brightness_controll_type_id_int=VALUES(brightness_controll_type_id_int),brightness_controll_type_name=VALUES(brightness_controll_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->brightness_controll_type_id, $this->brightness_controll_type_id_int, $this->brightness_controll_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>