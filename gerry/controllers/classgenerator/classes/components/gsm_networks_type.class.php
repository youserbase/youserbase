<?php
class gsm_networks_type
{

	protected static $instance = null;
	private static $table = 'gsm_networks_type';
	public static $data = array();
	public static $gsm_networks_type_id;
	public static $gsm_networks_type_name;
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


	private function Init($gsm_networks_type_id = null)
	{
		if($gsm_networks_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE gsm_networks_type_id = ?;", $gsm_networks_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('gsm_networks_type_id' => $line['gsm_networks_type_id'], 'gsm_networks_type_name' => $line['gsm_networks_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($gsm_networks_type_id = null)
	{
		if($gsm_networks_type_id !== null)
		{
			self::Init($gsm_networks_type_id);
		}
		return self::$data;
	}

	public function Set($gsm_networks_type_id = null, $gsm_networks_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($gsm_networks_type_id !== null)
		{
			self::$gsm_networks_type_id = $gsm_networks_type_id;
		}		if($gsm_networks_type_name !== null)
		{
			self::$gsm_networks_type_name = $gsm_networks_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES gsm_networks_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO gsm_networks_type (gsm_networks_type_id, gsm_networks_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE gsm_networks_type_id=VALUES(gsm_networks_type_id),gsm_networks_type_name=VALUES(gsm_networks_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->gsm_networks_type_id, $this->gsm_networks_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>