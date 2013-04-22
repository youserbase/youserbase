<?php
class power_output_type
{

	protected static $instance = null;
	private static $table = 'power_output_type';
	public static $data = array();
	public static $power_output_type_id;
	public static $power_output_type_name;
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


	private function Init($power_output_type_id = null)
	{
		if($power_output_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE power_output_type_id = ?;", $power_output_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('power_output_type_id' => $line['power_output_type_id'], 'power_output_type_name' => $line['power_output_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($power_output_type_id = null)
	{
		if($power_output_type_id !== null)
		{
			self::Init($power_output_type_id);
		}
		return self::$data;
	}

	public function Set($power_output_type_id = null, $power_output_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($power_output_type_id !== null)
		{
			self::$power_output_type_id = $power_output_type_id;
		}		if($power_output_type_name !== null)
		{
			self::$power_output_type_name = $power_output_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES power_output_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO power_output_type (power_output_type_id, power_output_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE power_output_type_id=VALUES(power_output_type_id),power_output_type_name=VALUES(power_output_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->power_output_type_id, $this->power_output_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>