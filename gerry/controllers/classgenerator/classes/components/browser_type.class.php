<?php
class browser_type
{

	protected static $instance = null;
	private static $table = 'browser_type';
	public static $data = array();
	public static $browser_type_id;
	public static $browser_type_id_int;
	public static $browser_type_name;
	public static $browser_type_version;
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


	private function Init($browser_type_id = null)
	{
		if($browser_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE browser_type_id = ?;", $browser_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('browser_type_id' => $line['browser_type_id'], 'browser_type_id_int' => $line['browser_type_id_int'], 'browser_type_name' => $line['browser_type_name'], 'browser_type_version' => $line['browser_type_version'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($browser_type_id = null)
	{
		if($browser_type_id !== null)
		{
			self::Init($browser_type_id);
		}
		return self::$data;
	}

	public function Set($browser_type_id = null, $browser_type_id_int = null, $browser_type_name = null, $browser_type_version = null, $timestamp = null, $youser_id = null)
	{
		if($browser_type_id !== null)
		{
			self::$browser_type_id = $browser_type_id;
		}		if($browser_type_id_int !== null)
		{
			self::$browser_type_id_int = $browser_type_id_int;
		}		if($browser_type_name !== null)
		{
			self::$browser_type_name = $browser_type_name;
		}		if($browser_type_version !== null)
		{
			self::$browser_type_version = $browser_type_version;
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
		DBManager::Get('devices')->query("LOCK TABLES browser_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO browser_type (browser_type_id, browser_type_id_int, browser_type_name, browser_type_version, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE browser_type_id=VALUES(browser_type_id),browser_type_id_int=VALUES(browser_type_id_int),browser_type_name=VALUES(browser_type_name),browser_type_version=VALUES(browser_type_version),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->browser_type_id, $this->browser_type_id_int, $this->browser_type_name, $this->browser_type_version, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>