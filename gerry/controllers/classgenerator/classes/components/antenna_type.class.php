<?php
class antenna_type
{

	protected static $instance = null;
	private static $table = 'antenna_type';
	public static $data = array();
	public static $antenna_type_id;
	public static $antenna_type_name;
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


	private function Init($antenna_type_id = null)
	{
		if($antenna_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE antenna_type_id = ?;", $antenna_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('antenna_type_id' => $line['antenna_type_id'], 'antenna_type_name' => $line['antenna_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($antenna_type_id = null)
	{
		if($antenna_type_id !== null)
		{
			self::Init($antenna_type_id);
		}
		return self::$data;
	}

	public function Set($antenna_type_id = null, $antenna_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($antenna_type_id !== null)
		{
			self::$antenna_type_id = $antenna_type_id;
		}		if($antenna_type_name !== null)
		{
			self::$antenna_type_name = $antenna_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES antenna_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO antenna_type (antenna_type_id, antenna_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE antenna_type_id=VALUES(antenna_type_id),antenna_type_name=VALUES(antenna_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->antenna_type_id, $this->antenna_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>