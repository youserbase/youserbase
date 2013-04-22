<?php
class hspa_networks_type
{

	protected static $instance = null;
	private static $table = 'hspa_networks_type';
	public static $data = array();
	public static $hspa_networks_type_id;
	public static $hspa_networks_type_name;
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


	private function Init($hspa_networks_type_id = null)
	{
		if($hspa_networks_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE hspa_networks_type_id = ?;", $hspa_networks_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('hspa_networks_type_id' => $line['hspa_networks_type_id'], 'hspa_networks_type_name' => $line['hspa_networks_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($hspa_networks_type_id = null)
	{
		if($hspa_networks_type_id !== null)
		{
			self::Init($hspa_networks_type_id);
		}
		return self::$data;
	}

	public function Set($hspa_networks_type_id = null, $hspa_networks_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($hspa_networks_type_id !== null)
		{
			self::$hspa_networks_type_id = $hspa_networks_type_id;
		}		if($hspa_networks_type_name !== null)
		{
			self::$hspa_networks_type_name = $hspa_networks_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES hspa_networks_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO hspa_networks_type (hspa_networks_type_id, hspa_networks_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE hspa_networks_type_id=VALUES(hspa_networks_type_id),hspa_networks_type_name=VALUES(hspa_networks_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->hspa_networks_type_id, $this->hspa_networks_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>