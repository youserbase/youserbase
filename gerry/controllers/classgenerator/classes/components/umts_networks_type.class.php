<?php
class umts_networks_type
{

	protected static $instance = null;
	private static $table = 'umts_networks_type';
	public static $data = array();
	public static $umts_networks_type_id;
	public static $umts_networks_type_name;
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


	private function Init($umts_networks_type_id = null)
	{
		if($umts_networks_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE umts_networks_type_id = ?;", $umts_networks_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('umts_networks_type_id' => $line['umts_networks_type_id'], 'umts_networks_type_name' => $line['umts_networks_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($umts_networks_type_id = null)
	{
		if($umts_networks_type_id !== null)
		{
			self::Init($umts_networks_type_id);
		}
		return self::$data;
	}

	public function Set($umts_networks_type_id = null, $umts_networks_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($umts_networks_type_id !== null)
		{
			self::$umts_networks_type_id = $umts_networks_type_id;
		}		if($umts_networks_type_name !== null)
		{
			self::$umts_networks_type_name = $umts_networks_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES umts_networks_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO umts_networks_type (umts_networks_type_id, umts_networks_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE umts_networks_type_id=VALUES(umts_networks_type_id),umts_networks_type_name=VALUES(umts_networks_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->umts_networks_type_id, $this->umts_networks_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>