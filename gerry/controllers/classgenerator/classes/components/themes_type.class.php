<?php
class themes_type
{

	protected static $instance = null;
	private static $table = 'themes_type';
	public static $data = array();
	public static $themes_type_id;
	public static $themes_type_name;
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


	private function Init($themes_type_id = null)
	{
		if($themes_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE themes_type_id = ?;", $themes_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('themes_type_id' => $line['themes_type_id'], 'themes_type_name' => $line['themes_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($themes_type_id = null)
	{
		if($themes_type_id !== null)
		{
			self::Init($themes_type_id);
		}
		return self::$data;
	}

	public function Set($themes_type_id = null, $themes_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($themes_type_id !== null)
		{
			self::$themes_type_id = $themes_type_id;
		}		if($themes_type_name !== null)
		{
			self::$themes_type_name = $themes_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES themes_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO themes_type (themes_type_id, themes_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE themes_type_id=VALUES(themes_type_id),themes_type_name=VALUES(themes_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->themes_type_id, $this->themes_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>