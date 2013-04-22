<?php
class phone_network_type
{

	protected static $instance = null;
	private static $table = 'phone_network_type';
	public static $data = array();
	public static $phone_network_type_id;
	public static $phone_network_type_id_int;
	public static $phone_network_type_name;
	public static $phone_network_type_frequency;
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


	private function Init($phone_network_type_id = null)
	{
		if($phone_network_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE phone_network_type_id = ?;", $phone_network_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('phone_network_type_id' => $line['phone_network_type_id'], 'phone_network_type_id_int' => $line['phone_network_type_id_int'], 'phone_network_type_name' => $line['phone_network_type_name'], 'phone_network_type_frequency' => $line['phone_network_type_frequency'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($phone_network_type_id = null)
	{
		if($phone_network_type_id !== null)
		{
			self::Init($phone_network_type_id);
		}
		return self::$data;
	}

	public function Set($phone_network_type_id = null, $phone_network_type_id_int = null, $phone_network_type_name = null, $phone_network_type_frequency = null, $timestamp = null, $youser_id = null)
	{
		if($phone_network_type_id !== null)
		{
			self::$phone_network_type_id = $phone_network_type_id;
		}		if($phone_network_type_id_int !== null)
		{
			self::$phone_network_type_id_int = $phone_network_type_id_int;
		}		if($phone_network_type_name !== null)
		{
			self::$phone_network_type_name = $phone_network_type_name;
		}		if($phone_network_type_frequency !== null)
		{
			self::$phone_network_type_frequency = $phone_network_type_frequency;
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
		DBManager::Get('devices')->query("LOCK TABLES phone_network_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO phone_network_type (phone_network_type_id, phone_network_type_id_int, phone_network_type_name, phone_network_type_frequency, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE phone_network_type_id=VALUES(phone_network_type_id),phone_network_type_id_int=VALUES(phone_network_type_id_int),phone_network_type_name=VALUES(phone_network_type_name),phone_network_type_frequency=VALUES(phone_network_type_frequency),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->phone_network_type_id, $this->phone_network_type_id_int, $this->phone_network_type_name, $this->phone_network_type_frequency, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>