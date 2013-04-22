<?php
class device_type
{

	protected static $instance = null;
	private static $table = 'device_type';
	public static $data = array();
	public static $device_type_id;
	public static $device_type_name;
	public static $device_type_class;
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


	private function Init($device_type_id = null)
	{
		if($device_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE device_type_id = ?;", $device_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('device_type_id' => $line['device_type_id'], 'device_type_name' => $line['device_type_name'], 'device_type_class' => $line['device_type_class'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($device_type_id = null)
	{
		if($device_type_id !== null)
		{
			self::Init($device_type_id);
		}
		return self::$data;
	}

	public function Set($device_type_id = null, $device_type_name = null, $device_type_class = null, $timestamp = null, $youser_id = null)
	{
		if($device_type_id !== null)
		{
			self::$device_type_id = $device_type_id;
		}		if($device_type_name !== null)
		{
			self::$device_type_name = $device_type_name;
		}		if($device_type_class !== null)
		{
			self::$device_type_class = $device_type_class;
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
		DBManager::Get('devices')->query("LOCK TABLES device_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO device_type (device_type_id, device_type_name, device_type_class, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE device_type_id=VALUES(device_type_id),device_type_name=VALUES(device_type_name),device_type_class=VALUES(device_type_class),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->device_type_id, $this->device_type_name, $this->device_type_class, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>