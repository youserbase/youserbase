<?php
class call_notification_type
{

	protected static $instance = null;
	private static $table = 'call_notification_type';
	public static $data = array();
	public static $call_notification_type_id;
	public static $call_notification_type_name;
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


	private function Init($call_notification_type_id = null)
	{
		if($call_notification_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE call_notification_type_id = ?;", $call_notification_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('call_notification_type_id' => $line['call_notification_type_id'], 'call_notification_type_name' => $line['call_notification_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($call_notification_type_id = null)
	{
		if($call_notification_type_id !== null)
		{
			self::Init($call_notification_type_id);
		}
		return self::$data;
	}

	public function Set($call_notification_type_id = null, $call_notification_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($call_notification_type_id !== null)
		{
			self::$call_notification_type_id = $call_notification_type_id;
		}		if($call_notification_type_name !== null)
		{
			self::$call_notification_type_name = $call_notification_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES call_notification_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO call_notification_type (call_notification_type_id, call_notification_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE call_notification_type_id=VALUES(call_notification_type_id),call_notification_type_name=VALUES(call_notification_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->call_notification_type_id, $this->call_notification_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>