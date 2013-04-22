<?php
class size_units_type
{

	protected static $instance = null;
	private static $table = 'size_units_type';
	public static $data = array();
	public static $size_units_type_id;
	public static $size_units_type_name;
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


	private function Init($size_units_type_id = null)
	{
		if($size_units_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE size_units_type_id = ?;", $size_units_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('size_units_type_id' => $line['size_units_type_id'], 'size_units_type_name' => $line['size_units_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($size_units_type_id = null)
	{
		if($size_units_type_id !== null)
		{
			self::Init($size_units_type_id);
		}
		return self::$data;
	}

	public function Set($size_units_type_id = null, $size_units_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($size_units_type_id !== null)
		{
			self::$size_units_type_id = $size_units_type_id;
		}		if($size_units_type_name !== null)
		{
			self::$size_units_type_name = $size_units_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES size_units_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO size_units_type (size_units_type_id, size_units_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE size_units_type_id=VALUES(size_units_type_id),size_units_type_name=VALUES(size_units_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->size_units_type_id, $this->size_units_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>