<?php
class cpuspeed_units_type
{

	protected static $instance = null;
	private static $table = 'cpuspeed_units_type';
	public static $data = array();
	public static $cpuspeed_units_type_id;
	public static $cpuspeed_units_type_name;
	public static $youser_id;
	public static $timestamp;


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


	private function Init($cpuspeed_units_type_id = null)
	{
		if($cpuspeed_units_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE cpuspeed_units_type_id = ?;", $cpuspeed_units_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('cpuspeed_units_type_id' => $line['cpuspeed_units_type_id'], 'cpuspeed_units_type_name' => $line['cpuspeed_units_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($cpuspeed_units_type_id = null)
	{
		if($cpuspeed_units_type_id !== null)
		{
			self::Init($cpuspeed_units_type_id);
		}
		return self::$data;
	}

	public function Set($cpuspeed_units_type_id = null, $cpuspeed_units_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($cpuspeed_units_type_id !== null)
		{
			self::$cpuspeed_units_type_id = $cpuspeed_units_type_id;
		}		if($cpuspeed_units_type_name !== null)
		{
			self::$cpuspeed_units_type_name = $cpuspeed_units_type_name;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES cpuspeed_units_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO cpuspeed_units_type (cpuspeed_units_type_id, cpuspeed_units_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE cpuspeed_units_type_id=VALUES(cpuspeed_units_type_id),cpuspeed_units_type_name=VALUES(cpuspeed_units_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->cpuspeed_units_type_id, $this->cpuspeed_units_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>