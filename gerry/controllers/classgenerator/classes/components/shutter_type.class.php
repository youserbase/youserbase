<?php
class shutter_type
{

	protected static $instance = null;
	private static $table = 'shutter_type';
	public static $data = array();
	public static $shutter_type_id;
	public static $shutter_type_id_int;
	public static $shutter_type_name;
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


	private function Init($shutter_type_id = null)
	{
		if($shutter_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE shutter_type_id = ?;", $shutter_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('shutter_type_id' => $line['shutter_type_id'], 'shutter_type_id_int' => $line['shutter_type_id_int'], 'shutter_type_name' => $line['shutter_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($shutter_type_id = null)
	{
		if($shutter_type_id !== null)
		{
			self::Init($shutter_type_id);
		}
		return self::$data;
	}

	public function Set($shutter_type_id = null, $shutter_type_id_int = null, $shutter_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($shutter_type_id !== null)
		{
			self::$shutter_type_id = $shutter_type_id;
		}		if($shutter_type_id_int !== null)
		{
			self::$shutter_type_id_int = $shutter_type_id_int;
		}		if($shutter_type_name !== null)
		{
			self::$shutter_type_name = $shutter_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES shutter_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO shutter_type (shutter_type_id, shutter_type_id_int, shutter_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE shutter_type_id=VALUES(shutter_type_id),shutter_type_id_int=VALUES(shutter_type_id_int),shutter_type_name=VALUES(shutter_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->shutter_type_id, $this->shutter_type_id_int, $this->shutter_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>