<?php
class nav_usage_for_type
{

	protected static $instance = null;
	private static $table = 'nav_usage_for_type';
	public static $data = array();
	public static $nav_usage_for_type_id;
	public static $nav_usage_for_type_name;
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


	private function Init($nav_usage_for_type_id = null)
	{
		if($nav_usage_for_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE nav_usage_for_type_id = ?;", $nav_usage_for_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('nav_usage_for_type_id' => $line['nav_usage_for_type_id'], 'nav_usage_for_type_name' => $line['nav_usage_for_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($nav_usage_for_type_id = null)
	{
		if($nav_usage_for_type_id !== null)
		{
			self::Init($nav_usage_for_type_id);
		}
		return self::$data;
	}

	public function Set($nav_usage_for_type_id = null, $nav_usage_for_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($nav_usage_for_type_id !== null)
		{
			self::$nav_usage_for_type_id = $nav_usage_for_type_id;
		}		if($nav_usage_for_type_name !== null)
		{
			self::$nav_usage_for_type_name = $nav_usage_for_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES nav_usage_for_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO nav_usage_for_type (nav_usage_for_type_id, nav_usage_for_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE nav_usage_for_type_id=VALUES(nav_usage_for_type_id),nav_usage_for_type_name=VALUES(nav_usage_for_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->nav_usage_for_type_id, $this->nav_usage_for_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>