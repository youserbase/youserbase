<?php
class cdma_networks_type
{

	protected static $instance = null;
	private static $table = 'cdma_networks_type';
	public static $data = array();
	public static $cdma_networks_type_id;
	public static $cdma_networks_type_name;
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


	private function Init($cdma_networks_type_id = null)
	{
		if($cdma_networks_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE cdma_networks_type_id = ?;", $cdma_networks_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('cdma_networks_type_id' => $line['cdma_networks_type_id'], 'cdma_networks_type_name' => $line['cdma_networks_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($cdma_networks_type_id = null)
	{
		if($cdma_networks_type_id !== null)
		{
			self::Init($cdma_networks_type_id);
		}
		return self::$data;
	}

	public function Set($cdma_networks_type_id = null, $cdma_networks_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($cdma_networks_type_id !== null)
		{
			self::$cdma_networks_type_id = $cdma_networks_type_id;
		}		if($cdma_networks_type_name !== null)
		{
			self::$cdma_networks_type_name = $cdma_networks_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES cdma_networks_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO cdma_networks_type (cdma_networks_type_id, cdma_networks_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE cdma_networks_type_id=VALUES(cdma_networks_type_id),cdma_networks_type_name=VALUES(cdma_networks_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->cdma_networks_type_id, $this->cdma_networks_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>