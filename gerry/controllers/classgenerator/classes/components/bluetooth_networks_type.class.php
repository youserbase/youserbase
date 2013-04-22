<?php
class bluetooth_networks_type
{

	protected static $instance = null;
	private static $table = 'bluetooth_networks_type';
	public static $data = array();
	public static $bluetooth_networks_type_id;
	public static $bluetooth_networks_type_name;
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


	private function Init($bluetooth_networks_type_id = null)
	{
		if($bluetooth_networks_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE bluetooth_networks_type_id = ?;", $bluetooth_networks_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('bluetooth_networks_type_id' => $line['bluetooth_networks_type_id'], 'bluetooth_networks_type_name' => $line['bluetooth_networks_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($bluetooth_networks_type_id = null)
	{
		if($bluetooth_networks_type_id !== null)
		{
			self::Init($bluetooth_networks_type_id);
		}
		return self::$data;
	}

	public function Set($bluetooth_networks_type_id = null, $bluetooth_networks_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($bluetooth_networks_type_id !== null)
		{
			self::$bluetooth_networks_type_id = $bluetooth_networks_type_id;
		}		if($bluetooth_networks_type_name !== null)
		{
			self::$bluetooth_networks_type_name = $bluetooth_networks_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES bluetooth_networks_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO bluetooth_networks_type (bluetooth_networks_type_id, bluetooth_networks_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE bluetooth_networks_type_id=VALUES(bluetooth_networks_type_id),bluetooth_networks_type_name=VALUES(bluetooth_networks_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->bluetooth_networks_type_id, $this->bluetooth_networks_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>