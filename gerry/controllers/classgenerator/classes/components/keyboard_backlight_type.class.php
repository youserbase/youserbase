<?php
class keyboard_backlight_type
{

	protected static $instance = null;
	private static $table = 'keyboard_backlight_type';
	public static $data = array();
	public static $keyboard_backlight_type_id;
	public static $keyboard_backlight_type_id_int;
	public static $keyboard_backlight_type_name;
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


	private function Init($keyboard_backlight_type_id = null)
	{
		if($keyboard_backlight_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE keyboard_backlight_type_id = ?;", $keyboard_backlight_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('keyboard_backlight_type_id' => $line['keyboard_backlight_type_id'], 'keyboard_backlight_type_id_int' => $line['keyboard_backlight_type_id_int'], 'keyboard_backlight_type_name' => $line['keyboard_backlight_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($keyboard_backlight_type_id = null)
	{
		if($keyboard_backlight_type_id !== null)
		{
			self::Init($keyboard_backlight_type_id);
		}
		return self::$data;
	}

	public function Set($keyboard_backlight_type_id = null, $keyboard_backlight_type_id_int = null, $keyboard_backlight_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($keyboard_backlight_type_id !== null)
		{
			self::$keyboard_backlight_type_id = $keyboard_backlight_type_id;
		}		if($keyboard_backlight_type_id_int !== null)
		{
			self::$keyboard_backlight_type_id_int = $keyboard_backlight_type_id_int;
		}		if($keyboard_backlight_type_name !== null)
		{
			self::$keyboard_backlight_type_name = $keyboard_backlight_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES keyboard_backlight_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO keyboard_backlight_type (keyboard_backlight_type_id, keyboard_backlight_type_id_int, keyboard_backlight_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE keyboard_backlight_type_id=VALUES(keyboard_backlight_type_id),keyboard_backlight_type_id_int=VALUES(keyboard_backlight_type_id_int),keyboard_backlight_type_name=VALUES(keyboard_backlight_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->keyboard_backlight_type_id, $this->keyboard_backlight_type_id_int, $this->keyboard_backlight_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>