<?php
class release_quarter_type
{

	protected static $instance = null;
	private static $table = 'release_quarter_type';
	public static $data = array();
	public static $release_quarter_type_id;
	public static $release_quarter_type_name;
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


	private function Init($release_quarter_type_id = null)
	{
		if($release_quarter_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE release_quarter_type_id = ?;", $release_quarter_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('release_quarter_type_id' => $line['release_quarter_type_id'], 'release_quarter_type_name' => $line['release_quarter_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($release_quarter_type_id = null)
	{
		if($release_quarter_type_id !== null)
		{
			self::Init($release_quarter_type_id);
		}
		return self::$data;
	}

	public function Set($release_quarter_type_id = null, $release_quarter_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($release_quarter_type_id !== null)
		{
			self::$release_quarter_type_id = $release_quarter_type_id;
		}		if($release_quarter_type_name !== null)
		{
			self::$release_quarter_type_name = $release_quarter_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES release_quarter_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO release_quarter_type (release_quarter_type_id, release_quarter_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE release_quarter_type_id=VALUES(release_quarter_type_id),release_quarter_type_name=VALUES(release_quarter_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->release_quarter_type_id, $this->release_quarter_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>