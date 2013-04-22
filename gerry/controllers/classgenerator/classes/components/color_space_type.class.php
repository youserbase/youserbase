<?php
class color_space_type
{

	protected static $instance = null;
	private static $table = 'color_space_type';
	public static $data = array();
	public static $color_space_type_id;
	public static $color_space_type_id_int;
	public static $color_space_type_name;
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


	private function Init($color_space_type_id = null)
	{
		if($color_space_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE color_space_type_id = ?;", $color_space_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('color_space_type_id' => $line['color_space_type_id'], 'color_space_type_id_int' => $line['color_space_type_id_int'], 'color_space_type_name' => $line['color_space_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($color_space_type_id = null)
	{
		if($color_space_type_id !== null)
		{
			self::Init($color_space_type_id);
		}
		return self::$data;
	}

	public function Set($color_space_type_id = null, $color_space_type_id_int = null, $color_space_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($color_space_type_id !== null)
		{
			self::$color_space_type_id = $color_space_type_id;
		}		if($color_space_type_id_int !== null)
		{
			self::$color_space_type_id_int = $color_space_type_id_int;
		}		if($color_space_type_name !== null)
		{
			self::$color_space_type_name = $color_space_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES color_space_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO color_space_type (color_space_type_id, color_space_type_id_int, color_space_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE color_space_type_id=VALUES(color_space_type_id),color_space_type_id_int=VALUES(color_space_type_id_int),color_space_type_name=VALUES(color_space_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->color_space_type_id, $this->color_space_type_id_int, $this->color_space_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>