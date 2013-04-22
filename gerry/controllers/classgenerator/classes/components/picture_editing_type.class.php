<?php
class picture_editing_type
{

	protected static $instance = null;
	private static $table = 'picture_editing_type';
	public static $data = array();
	public static $picture_editing_type_id;
	public static $picture_editing_type_id_int;
	public static $picture_editing_type_name;
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


	private function Init($picture_editing_type_id = null)
	{
		if($picture_editing_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE picture_editing_type_id = ?;", $picture_editing_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('picture_editing_type_id' => $line['picture_editing_type_id'], 'picture_editing_type_id_int' => $line['picture_editing_type_id_int'], 'picture_editing_type_name' => $line['picture_editing_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($picture_editing_type_id = null)
	{
		if($picture_editing_type_id !== null)
		{
			self::Init($picture_editing_type_id);
		}
		return self::$data;
	}

	public function Set($picture_editing_type_id = null, $picture_editing_type_id_int = null, $picture_editing_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($picture_editing_type_id !== null)
		{
			self::$picture_editing_type_id = $picture_editing_type_id;
		}		if($picture_editing_type_id_int !== null)
		{
			self::$picture_editing_type_id_int = $picture_editing_type_id_int;
		}		if($picture_editing_type_name !== null)
		{
			self::$picture_editing_type_name = $picture_editing_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES picture_editing_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO picture_editing_type (picture_editing_type_id, picture_editing_type_id_int, picture_editing_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE picture_editing_type_id=VALUES(picture_editing_type_id),picture_editing_type_id_int=VALUES(picture_editing_type_id_int),picture_editing_type_name=VALUES(picture_editing_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->picture_editing_type_id, $this->picture_editing_type_id_int, $this->picture_editing_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>