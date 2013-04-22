<?php
class white_balance_type
{

	protected static $instance = null;
	private static $table = 'white_balance_type';
	public static $data = array();
	public static $white_balance_type_id;
	public static $white_balance_type_name;
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


	private function Init($white_balance_type_id = null)
	{
		if($white_balance_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE white_balance_type_id = ?;", $white_balance_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('white_balance_type_id' => $line['white_balance_type_id'], 'white_balance_type_name' => $line['white_balance_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($white_balance_type_id = null)
	{
		if($white_balance_type_id !== null)
		{
			self::Init($white_balance_type_id);
		}
		return self::$data;
	}

	public function Set($white_balance_type_id = null, $white_balance_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($white_balance_type_id !== null)
		{
			self::$white_balance_type_id = $white_balance_type_id;
		}		if($white_balance_type_name !== null)
		{
			self::$white_balance_type_name = $white_balance_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES white_balance_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO white_balance_type (white_balance_type_id, white_balance_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE white_balance_type_id=VALUES(white_balance_type_id),white_balance_type_name=VALUES(white_balance_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->white_balance_type_id, $this->white_balance_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>