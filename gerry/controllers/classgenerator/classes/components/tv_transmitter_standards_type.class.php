<?php
class tv_transmitter_standards_type
{

	protected static $instance = null;
	private static $table = 'tv_transmitter_standards_type';
	public static $data = array();
	public static $tv_transmitter_standards_type_id;
	public static $tv_transmitter_standards_type_id_int;
	public static $tv_transmitter_standards_type_name;
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


	private function Init($tv_transmitter_standards_type_id = null)
	{
		if($tv_transmitter_standards_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE tv_transmitter_standards_type_id = ?;", $tv_transmitter_standards_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('tv_transmitter_standards_type_id' => $line['tv_transmitter_standards_type_id'], 'tv_transmitter_standards_type_id_int' => $line['tv_transmitter_standards_type_id_int'], 'tv_transmitter_standards_type_name' => $line['tv_transmitter_standards_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($tv_transmitter_standards_type_id = null)
	{
		if($tv_transmitter_standards_type_id !== null)
		{
			self::Init($tv_transmitter_standards_type_id);
		}
		return self::$data;
	}

	public function Set($tv_transmitter_standards_type_id = null, $tv_transmitter_standards_type_id_int = null, $tv_transmitter_standards_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($tv_transmitter_standards_type_id !== null)
		{
			self::$tv_transmitter_standards_type_id = $tv_transmitter_standards_type_id;
		}		if($tv_transmitter_standards_type_id_int !== null)
		{
			self::$tv_transmitter_standards_type_id_int = $tv_transmitter_standards_type_id_int;
		}		if($tv_transmitter_standards_type_name !== null)
		{
			self::$tv_transmitter_standards_type_name = $tv_transmitter_standards_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES tv_transmitter_standards_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO tv_transmitter_standards_type (tv_transmitter_standards_type_id, tv_transmitter_standards_type_id_int, tv_transmitter_standards_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE tv_transmitter_standards_type_id=VALUES(tv_transmitter_standards_type_id),tv_transmitter_standards_type_id_int=VALUES(tv_transmitter_standards_type_id_int),tv_transmitter_standards_type_name=VALUES(tv_transmitter_standards_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->tv_transmitter_standards_type_id, $this->tv_transmitter_standards_type_id_int, $this->tv_transmitter_standards_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>