<?php
class camera_programs_type
{

	protected static $instance = null;
	private static $table = 'camera_programs_type';
	public static $data = array();
	public static $camera_programs_type_id;
	public static $camera_programs_type_id_int;
	public static $camera_programs_type_name;
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


	private function Init($camera_programs_type_id = null)
	{
		if($camera_programs_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE camera_programs_type_id = ?;", $camera_programs_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('camera_programs_type_id' => $line['camera_programs_type_id'], 'camera_programs_type_id_int' => $line['camera_programs_type_id_int'], 'camera_programs_type_name' => $line['camera_programs_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($camera_programs_type_id = null)
	{
		if($camera_programs_type_id !== null)
		{
			self::Init($camera_programs_type_id);
		}
		return self::$data;
	}

	public function Set($camera_programs_type_id = null, $camera_programs_type_id_int = null, $camera_programs_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($camera_programs_type_id !== null)
		{
			self::$camera_programs_type_id = $camera_programs_type_id;
		}		if($camera_programs_type_id_int !== null)
		{
			self::$camera_programs_type_id_int = $camera_programs_type_id_int;
		}		if($camera_programs_type_name !== null)
		{
			self::$camera_programs_type_name = $camera_programs_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES camera_programs_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO camera_programs_type (camera_programs_type_id, camera_programs_type_id_int, camera_programs_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE camera_programs_type_id=VALUES(camera_programs_type_id),camera_programs_type_id_int=VALUES(camera_programs_type_id_int),camera_programs_type_name=VALUES(camera_programs_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->camera_programs_type_id, $this->camera_programs_type_id_int, $this->camera_programs_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>