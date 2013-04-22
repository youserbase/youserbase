<?php
class businesscard_type
{

	protected static $instance = null;
	private static $table = 'businesscard_type';
	public static $data = array();
	public static $businesscard_type_id;
	public static $businesscard_type_name;
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


	private function Init($businesscard_type_id = null)
	{
		if($businesscard_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE businesscard_type_id = ?;", $businesscard_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('businesscard_type_id' => $line['businesscard_type_id'], 'businesscard_type_name' => $line['businesscard_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($businesscard_type_id = null)
	{
		if($businesscard_type_id !== null)
		{
			self::Init($businesscard_type_id);
		}
		return self::$data;
	}

	public function Set($businesscard_type_id = null, $businesscard_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($businesscard_type_id !== null)
		{
			self::$businesscard_type_id = $businesscard_type_id;
		}		if($businesscard_type_name !== null)
		{
			self::$businesscard_type_name = $businesscard_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES businesscard_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO businesscard_type (businesscard_type_id, businesscard_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE businesscard_type_id=VALUES(businesscard_type_id),businesscard_type_name=VALUES(businesscard_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->businesscard_type_id, $this->businesscard_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>