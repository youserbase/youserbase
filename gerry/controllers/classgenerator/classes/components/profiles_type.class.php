<?php
class profiles_type
{

	protected static $instance = null;
	private static $table = 'profiles_type';
	public static $data = array();
	public static $profiles_type_id;
	public static $profiles_type_name;
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


	private function Init($profiles_type_id = null)
	{
		if($profiles_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE profiles_type_id = ?;", $profiles_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('profiles_type_id' => $line['profiles_type_id'], 'profiles_type_name' => $line['profiles_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($profiles_type_id = null)
	{
		if($profiles_type_id !== null)
		{
			self::Init($profiles_type_id);
		}
		return self::$data;
	}

	public function Set($profiles_type_id = null, $profiles_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($profiles_type_id !== null)
		{
			self::$profiles_type_id = $profiles_type_id;
		}		if($profiles_type_name !== null)
		{
			self::$profiles_type_name = $profiles_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES profiles_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO profiles_type (profiles_type_id, profiles_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE profiles_type_id=VALUES(profiles_type_id),profiles_type_name=VALUES(profiles_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->profiles_type_id, $this->profiles_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>