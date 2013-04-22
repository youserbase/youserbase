<?php
class music_format_type
{

	protected static $instance = null;
	private static $table = 'music_format_type';
	public static $data = array();
	public static $music_format_type_id;
	public static $music_format_type_id_int;
	public static $music_format_type_name;
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


	private function Init($music_format_type_id = null)
	{
		if($music_format_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE music_format_type_id = ?;", $music_format_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('music_format_type_id' => $line['music_format_type_id'], 'music_format_type_id_int' => $line['music_format_type_id_int'], 'music_format_type_name' => $line['music_format_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($music_format_type_id = null)
	{
		if($music_format_type_id !== null)
		{
			self::Init($music_format_type_id);
		}
		return self::$data;
	}

	public function Set($music_format_type_id = null, $music_format_type_id_int = null, $music_format_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($music_format_type_id !== null)
		{
			self::$music_format_type_id = $music_format_type_id;
		}		if($music_format_type_id_int !== null)
		{
			self::$music_format_type_id_int = $music_format_type_id_int;
		}		if($music_format_type_name !== null)
		{
			self::$music_format_type_name = $music_format_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES music_format_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO music_format_type (music_format_type_id, music_format_type_id_int, music_format_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE music_format_type_id=VALUES(music_format_type_id),music_format_type_id_int=VALUES(music_format_type_id_int),music_format_type_name=VALUES(music_format_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->music_format_type_id, $this->music_format_type_id_int, $this->music_format_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>