<?php
class camera_resolutions_type
{

	protected static $instance = null;
	private static $table = 'camera_resolutions_type';
	public static $data = array();
	public static $camera_resolutions_type_id;
	public static $camera_resolutions_type_id_int;
	public static $camera_resolutions_type_name;
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


	private function Init($camera_resolutions_type_id = null)
	{
		if($camera_resolutions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE camera_resolutions_type_id = ?;", $camera_resolutions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('camera_resolutions_type_id' => $line['camera_resolutions_type_id'], 'camera_resolutions_type_id_int' => $line['camera_resolutions_type_id_int'], 'camera_resolutions_type_name' => $line['camera_resolutions_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($camera_resolutions_type_id = null)
	{
		if($camera_resolutions_type_id !== null)
		{
			self::Init($camera_resolutions_type_id);
		}
		return self::$data;
	}

	public function Set($camera_resolutions_type_id = null, $camera_resolutions_type_id_int = null, $camera_resolutions_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($camera_resolutions_type_id !== null)
		{
			self::$camera_resolutions_type_id = $camera_resolutions_type_id;
		}		if($camera_resolutions_type_id_int !== null)
		{
			self::$camera_resolutions_type_id_int = $camera_resolutions_type_id_int;
		}		if($camera_resolutions_type_name !== null)
		{
			self::$camera_resolutions_type_name = $camera_resolutions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES camera_resolutions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO camera_resolutions_type (camera_resolutions_type_id, camera_resolutions_type_id_int, camera_resolutions_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE camera_resolutions_type_id=VALUES(camera_resolutions_type_id),camera_resolutions_type_id_int=VALUES(camera_resolutions_type_id_int),camera_resolutions_type_name=VALUES(camera_resolutions_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->camera_resolutions_type_id, $this->camera_resolutions_type_id_int, $this->camera_resolutions_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>