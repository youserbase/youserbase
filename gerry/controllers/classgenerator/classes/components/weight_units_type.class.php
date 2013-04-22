<?php
class weight_units_type
{

	protected static $instance = null;
	private static $table = 'weight_units_type';
	public static $data = array();
	public static $weight_units_type_id;
	public static $weight_units_type_name;
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


	private function Init($weight_units_type_id = null)
	{
		if($weight_units_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE weight_units_type_id = ?;", $weight_units_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('weight_units_type_id' => $line['weight_units_type_id'], 'weight_units_type_name' => $line['weight_units_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($weight_units_type_id = null)
	{
		if($weight_units_type_id !== null)
		{
			self::Init($weight_units_type_id);
		}
		return self::$data;
	}

	public function Set($weight_units_type_id = null, $weight_units_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($weight_units_type_id !== null)
		{
			self::$weight_units_type_id = $weight_units_type_id;
		}		if($weight_units_type_name !== null)
		{
			self::$weight_units_type_name = $weight_units_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES weight_units_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO weight_units_type (weight_units_type_id, weight_units_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE weight_units_type_id=VALUES(weight_units_type_id),weight_units_type_name=VALUES(weight_units_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->weight_units_type_id, $this->weight_units_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>