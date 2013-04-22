<?php
class wifi_networks_type
{

	protected static $instance = null;
	private static $table = 'wifi_networks_type';
	public static $data = array();
	public static $wifi_networks_type_id;
	public static $wifi_networks_type_name;
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


	private function Init($wifi_networks_type_id = null)
	{
		if($wifi_networks_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE wifi_networks_type_id = ?;", $wifi_networks_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('wifi_networks_type_id' => $line['wifi_networks_type_id'], 'wifi_networks_type_name' => $line['wifi_networks_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($wifi_networks_type_id = null)
	{
		if($wifi_networks_type_id !== null)
		{
			self::Init($wifi_networks_type_id);
		}
		return self::$data;
	}

	public function Set($wifi_networks_type_id = null, $wifi_networks_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($wifi_networks_type_id !== null)
		{
			self::$wifi_networks_type_id = $wifi_networks_type_id;
		}		if($wifi_networks_type_name !== null)
		{
			self::$wifi_networks_type_name = $wifi_networks_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES wifi_networks_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO wifi_networks_type (wifi_networks_type_id, wifi_networks_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE wifi_networks_type_id=VALUES(wifi_networks_type_id),wifi_networks_type_name=VALUES(wifi_networks_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->wifi_networks_type_id, $this->wifi_networks_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>