<?php
class body_type
{

	protected static $instance = null;
	private static $table = 'body_type';
	public static $data = array();
	public static $body_type_id;
	public static $body_type_name;
	public static $device_type;
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


	private function Init($body_type_id = null, $device_type = null)
	{
		if($body_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE body_type_id = ? AND device_type = ? ;", $body_type_id, $device_type)->to_array();
		}
		else{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE device_type = ? ORDER BY ".self::$table."_name".";", $device_type)->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('body_type_id' => $line['body_type_id'], 'body_type_name' => $line['body_type_name'], 'device_type' => $line['device_type'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($body_type_id = null, $device_type = null)
	{
		if($body_type_id !== null)
		{
			self::Init($body_type_id, $device_type);
		}
		else self::Init($body_type_id, $device_type);
		return self::$data;
	}

	public function Set($body_type_id = null, $body_type_name = null, $device_type = null, $timestamp = null, $youser_id = null)
	{
		if($body_type_id !== null)
		{
			self::$body_type_id = $body_type_id;
		}		if($body_type_name !== null)
		{
			self::$body_type_name = $body_type_name;
		}		if($device_type !== null)
		{
			self::$device_type = $device_type;
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
		DBManager::Get('devices')->query("LOCK TABLES body_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO body_type (body_type_id, body_type_name, device_type, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE body_type_id=VALUES(body_type_id),body_type_name=VALUES(body_type_name),device_type=VALUES(device_type),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->body_type_id, $this->body_type_name, $this->device_type, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>