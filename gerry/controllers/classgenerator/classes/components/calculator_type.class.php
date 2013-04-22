<?php
class calculator_type
{

	protected static $instance = null;
	private static $table = 'calculator_type';
	public static $data = array();
	public static $calculator_type_id;
	public static $calculator_type_name;
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


	private function Init($calculator_type_id = null)
	{
		if($calculator_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE calculator_type_id = ?;", $calculator_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('calculator_type_id' => $line['calculator_type_id'], 'calculator_type_name' => $line['calculator_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($calculator_type_id = null)
	{
		if($calculator_type_id !== null)
		{
			self::Init($calculator_type_id);
		}
		return self::$data;
	}

	public function Set($calculator_type_id = null, $calculator_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($calculator_type_id !== null)
		{
			self::$calculator_type_id = $calculator_type_id;
		}		if($calculator_type_name !== null)
		{
			self::$calculator_type_name = $calculator_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES calculator_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO calculator_type (calculator_type_id, calculator_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE calculator_type_id=VALUES(calculator_type_id),calculator_type_name=VALUES(calculator_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->calculator_type_id, $this->calculator_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>