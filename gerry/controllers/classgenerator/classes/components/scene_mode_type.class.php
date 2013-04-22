<?php
class scene_mode_type
{

	protected static $instance = null;
	private static $table = 'scene_mode_type';
	public static $data = array();
	public static $scene_mode_type_id;
	public static $scene_mode_type_name;
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


	private function Init($scene_mode_type_id = null)
	{
		if($scene_mode_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE scene_mode_type_id = ?;", $scene_mode_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('scene_mode_type_id' => $line['scene_mode_type_id'], 'scene_mode_type_name' => $line['scene_mode_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($scene_mode_type_id = null)
	{
		if($scene_mode_type_id !== null)
		{
			self::Init($scene_mode_type_id);
		}
		return self::$data;
	}

	public function Set($scene_mode_type_id = null, $scene_mode_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($scene_mode_type_id !== null)
		{
			self::$scene_mode_type_id = $scene_mode_type_id;
		}		if($scene_mode_type_name !== null)
		{
			self::$scene_mode_type_name = $scene_mode_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES scene_mode_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO scene_mode_type (scene_mode_type_id, scene_mode_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE scene_mode_type_id=VALUES(scene_mode_type_id),scene_mode_type_name=VALUES(scene_mode_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->scene_mode_type_id, $this->scene_mode_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>