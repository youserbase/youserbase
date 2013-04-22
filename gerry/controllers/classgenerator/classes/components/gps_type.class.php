<?php
class gps_type
{

	protected static $instance = null;
	private static $table = 'gps_type';
	public static $data = array();
	public static $gps_type_id;
	public static $gps_type_id_int;
	public static $gps_type_name;
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


	private function Init($gps_type_id = null)
	{
		if($gps_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE gps_type_id = ?;", $gps_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('gps_type_id' => $line['gps_type_id'], 'gps_type_id_int' => $line['gps_type_id_int'], 'gps_type_name' => $line['gps_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($gps_type_id = null)
	{
		if($gps_type_id !== null)
		{
			self::Init($gps_type_id);
		}
		return self::$data;
	}

	public function Set($gps_type_id = null, $gps_type_id_int = null, $gps_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($gps_type_id !== null)
		{
			self::$gps_type_id = $gps_type_id;
		}		if($gps_type_id_int !== null)
		{
			self::$gps_type_id_int = $gps_type_id_int;
		}		if($gps_type_name !== null)
		{
			self::$gps_type_name = $gps_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES gps_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO gps_type (gps_type_id, gps_type_id_int, gps_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE gps_type_id=VALUES(gps_type_id),gps_type_id_int=VALUES(gps_type_id_int),gps_type_name=VALUES(gps_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->gps_type_id, $this->gps_type_id_int, $this->gps_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>