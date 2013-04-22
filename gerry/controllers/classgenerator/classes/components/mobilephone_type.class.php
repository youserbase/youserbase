<?php
class mobilephone_type
{

	protected static $instance = null;
	private static $table = 'mobilephone_type';
	public static $data = array();
	public static $mobilephone_type_id;
	public static $mobilephone_type_name;
	public static $device_type;
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


	private function Init($mobilephone_type_id = null)
	{
		if($mobilephone_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE mobilephone_type_id = ?;", $mobilephone_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('mobilephone_type_id' => $line['mobilephone_type_id'], 'mobilephone_type_name' => $line['mobilephone_type_name'], 'device_type' => $line['device_type'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($mobilephone_type_id = null)
	{
		if($mobilephone_type_id !== null)
		{
			self::Init($mobilephone_type_id);
		}
		return self::$data;
	}

	public function Set($mobilephone_type_id = null, $mobilephone_type_name = null, $device_type = null, $youser_id = null, $timestamp = null)
	{
		if($mobilephone_type_id !== null)
		{
			self::$mobilephone_type_id = $mobilephone_type_id;
		}		if($mobilephone_type_name !== null)
		{
			self::$mobilephone_type_name = $mobilephone_type_name;
		}		if($device_type !== null)
		{
			self::$device_type = $device_type;
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
		DBManager::Get('devices')->query("LOCK TABLES mobilephone_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO mobilephone_type (mobilephone_type_id, mobilephone_type_name, device_type, youser_id, timestamp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE mobilephone_type_id=VALUES(mobilephone_type_id),mobilephone_type_name=VALUES(mobilephone_type_name),device_type=VALUES(device_type),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->mobilephone_type_id, $this->mobilephone_type_name, $this->device_type, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>