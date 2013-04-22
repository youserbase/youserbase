<?php
class gps_chip_type
{

	protected static $instance = null;
	private static $table = 'gps_chip_type';
	public static $data = array();
	public static $gps_chip_type_id;
	public static $gps_chip_type_name;
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


	private function Init($gps_chip_type_id = null)
	{
		if($gps_chip_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE gps_chip_type_id = ?;", $gps_chip_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('gps_chip_type_id' => $line['gps_chip_type_id'], 'gps_chip_type_name' => $line['gps_chip_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($gps_chip_type_id = null)
	{
		if($gps_chip_type_id !== null)
		{
			self::Init($gps_chip_type_id);
		}
		return self::$data;
	}

	public function Set($gps_chip_type_id = null, $gps_chip_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($gps_chip_type_id !== null)
		{
			self::$gps_chip_type_id = $gps_chip_type_id;
		}		if($gps_chip_type_name !== null)
		{
			self::$gps_chip_type_name = $gps_chip_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES gps_chip_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO gps_chip_type (gps_chip_type_id, gps_chip_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE gps_chip_type_id=VALUES(gps_chip_type_id),gps_chip_type_name=VALUES(gps_chip_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->gps_chip_type_id, $this->gps_chip_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>