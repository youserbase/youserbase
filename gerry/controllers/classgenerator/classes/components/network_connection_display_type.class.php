<?php
class network_connection_display_type
{

	protected static $instance = null;
	private static $table = 'network_connection_display_type';
	public static $data = array();
	public static $network_connection_display_type_id;
	public static $network_connection_display_type_id_int;
	public static $network_connection_display_type_name;
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


	private function Init($network_connection_display_type_id = null)
	{
		if($network_connection_display_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE network_connection_display_type_id = ?;", $network_connection_display_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('network_connection_display_type_id' => $line['network_connection_display_type_id'], 'network_connection_display_type_id_int' => $line['network_connection_display_type_id_int'], 'network_connection_display_type_name' => $line['network_connection_display_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($network_connection_display_type_id = null)
	{
		if($network_connection_display_type_id !== null)
		{
			self::Init($network_connection_display_type_id);
		}
		return self::$data;
	}

	public function Set($network_connection_display_type_id = null, $network_connection_display_type_id_int = null, $network_connection_display_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($network_connection_display_type_id !== null)
		{
			self::$network_connection_display_type_id = $network_connection_display_type_id;
		}		if($network_connection_display_type_id_int !== null)
		{
			self::$network_connection_display_type_id_int = $network_connection_display_type_id_int;
		}		if($network_connection_display_type_name !== null)
		{
			self::$network_connection_display_type_name = $network_connection_display_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES network_connection_display_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO network_connection_display_type (network_connection_display_type_id, network_connection_display_type_id_int, network_connection_display_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE network_connection_display_type_id=VALUES(network_connection_display_type_id),network_connection_display_type_id_int=VALUES(network_connection_display_type_id_int),network_connection_display_type_name=VALUES(network_connection_display_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->network_connection_display_type_id, $this->network_connection_display_type_id_int, $this->network_connection_display_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>