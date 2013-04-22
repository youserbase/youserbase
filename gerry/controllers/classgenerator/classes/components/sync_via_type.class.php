<?php
class sync_via_type
{

	protected static $instance = null;
	private static $table = 'sync_via_type';
	public static $data = array();
	public static $sync_via_type_id;
	public static $sync_via_type_name;
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


	private function Init($sync_via_type_id = null)
	{
		if($sync_via_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE sync_via_type_id = ?;", $sync_via_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('sync_via_type_id' => $line['sync_via_type_id'], 'sync_via_type_name' => $line['sync_via_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($sync_via_type_id = null)
	{
		if($sync_via_type_id !== null)
		{
			self::Init($sync_via_type_id);
		}
		return self::$data;
	}

	public function Set($sync_via_type_id = null, $sync_via_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($sync_via_type_id !== null)
		{
			self::$sync_via_type_id = $sync_via_type_id;
		}		if($sync_via_type_name !== null)
		{
			self::$sync_via_type_name = $sync_via_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES sync_via_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO sync_via_type (sync_via_type_id, sync_via_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE sync_via_type_id=VALUES(sync_via_type_id),sync_via_type_name=VALUES(sync_via_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->sync_via_type_id, $this->sync_via_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>