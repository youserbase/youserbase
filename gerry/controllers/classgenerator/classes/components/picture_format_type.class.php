<?php
class picture_format_type
{

	protected static $instance = null;
	private static $table = 'picture_format_type';
	public static $data = array();
	public static $picture_format_type_id;
	public static $picture_format_type_id_int;
	public static $picture_format_type_name;
	public static $picture_format_type_short;
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


	private function Init($picture_format_type_id = null)
	{
		if($picture_format_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE picture_format_type_id = ?;", $picture_format_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('picture_format_type_id' => $line['picture_format_type_id'], 'picture_format_type_id_int' => $line['picture_format_type_id_int'], 'picture_format_type_name' => $line['picture_format_type_name'], 'picture_format_type_short' => $line['picture_format_type_short'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($picture_format_type_id = null)
	{
		if($picture_format_type_id !== null)
		{
			self::Init($picture_format_type_id);
		}
		return self::$data;
	}

	public function Set($picture_format_type_id = null, $picture_format_type_id_int = null, $picture_format_type_name = null, $picture_format_type_short = null, $timestamp = null, $youser_id = null)
	{
		if($picture_format_type_id !== null)
		{
			self::$picture_format_type_id = $picture_format_type_id;
		}		if($picture_format_type_id_int !== null)
		{
			self::$picture_format_type_id_int = $picture_format_type_id_int;
		}		if($picture_format_type_name !== null)
		{
			self::$picture_format_type_name = $picture_format_type_name;
		}		if($picture_format_type_short !== null)
		{
			self::$picture_format_type_short = $picture_format_type_short;
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
		DBManager::Get('devices')->query("LOCK TABLES picture_format_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO picture_format_type (picture_format_type_id, picture_format_type_id_int, picture_format_type_name, picture_format_type_short, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE picture_format_type_id=VALUES(picture_format_type_id),picture_format_type_id_int=VALUES(picture_format_type_id_int),picture_format_type_name=VALUES(picture_format_type_name),picture_format_type_short=VALUES(picture_format_type_short),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->picture_format_type_id, $this->picture_format_type_id_int, $this->picture_format_type_name, $this->picture_format_type_short, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>