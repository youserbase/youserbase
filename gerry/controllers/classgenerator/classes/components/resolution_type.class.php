<?php
class resolution_type
{

	protected static $instance = null;
	private static $table = 'resolution_type';
	public static $data = array();
	public static $resolution_type_id;
	public static $resolution_type_id_int;
	public static $resolution_type_name;
	public static $resolution_type_x;
	public static $resolution_type_y;
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


	private function Init($resolution_type_id = null)
	{
		if($resolution_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE resolution_type_id = ?;", $resolution_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('resolution_type_id' => $line['resolution_type_id'], 'resolution_type_id_int' => $line['resolution_type_id_int'], 'resolution_type_name' => $line['resolution_type_name'], 'resolution_type_x' => $line['resolution_type_x'], 'resolution_type_y' => $line['resolution_type_y'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($resolution_type_id = null)
	{
		if($resolution_type_id !== null)
		{
			self::Init($resolution_type_id);
		}
		return self::$data;
	}

	public function Set($resolution_type_id = null, $resolution_type_id_int = null, $resolution_type_name = null, $resolution_type_x = null, $resolution_type_y = null, $timestamp = null, $youser_id = null)
	{
		if($resolution_type_id !== null)
		{
			self::$resolution_type_id = $resolution_type_id;
		}		if($resolution_type_id_int !== null)
		{
			self::$resolution_type_id_int = $resolution_type_id_int;
		}		if($resolution_type_name !== null)
		{
			self::$resolution_type_name = $resolution_type_name;
		}		if($resolution_type_x !== null)
		{
			self::$resolution_type_x = $resolution_type_x;
		}		if($resolution_type_y !== null)
		{
			self::$resolution_type_y = $resolution_type_y;
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
		DBManager::Get('devices')->query("LOCK TABLES resolution_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO resolution_type (resolution_type_id, resolution_type_id_int, resolution_type_name, resolution_type_x, resolution_type_y, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE resolution_type_id=VALUES(resolution_type_id),resolution_type_id_int=VALUES(resolution_type_id_int),resolution_type_name=VALUES(resolution_type_name),resolution_type_x=VALUES(resolution_type_x),resolution_type_y=VALUES(resolution_type_y),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->resolution_type_id, $this->resolution_type_id_int, $this->resolution_type_name, $this->resolution_type_x, $this->resolution_type_y, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>