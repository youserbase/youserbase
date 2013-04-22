<?php
class sync_software_type
{

	protected static $instance = null;
	private static $table = 'sync_software_type';
	public static $data = array();
	public static $sync_software_type_id;
	public static $sync_software_type_id_int;
	public static $sync_software_type_name;
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


	private function Init($sync_software_type_id = null)
	{
		if($sync_software_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE sync_software_type_id = ?;", $sync_software_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('sync_software_type_id' => $line['sync_software_type_id'], 'sync_software_type_id_int' => $line['sync_software_type_id_int'], 'sync_software_type_name' => $line['sync_software_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($sync_software_type_id = null)
	{
		if($sync_software_type_id !== null)
		{
			self::Init($sync_software_type_id);
		}
		return self::$data;
	}

	public function Set($sync_software_type_id = null, $sync_software_type_id_int = null, $sync_software_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($sync_software_type_id !== null)
		{
			self::$sync_software_type_id = $sync_software_type_id;
		}		if($sync_software_type_id_int !== null)
		{
			self::$sync_software_type_id_int = $sync_software_type_id_int;
		}		if($sync_software_type_name !== null)
		{
			self::$sync_software_type_name = $sync_software_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES sync_software_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO sync_software_type (sync_software_type_id, sync_software_type_id_int, sync_software_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE sync_software_type_id=VALUES(sync_software_type_id),sync_software_type_id_int=VALUES(sync_software_type_id_int),sync_software_type_name=VALUES(sync_software_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->sync_software_type_id, $this->sync_software_type_id_int, $this->sync_software_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>