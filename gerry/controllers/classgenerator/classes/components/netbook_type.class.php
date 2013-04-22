<?php
class netbook_type
{

	protected static $instance = null;
	private static $table = 'netbook_type';
	public static $data = array();
	public static $netbook_type_id;
	public static $netbook_type_name;
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


	private function Init($netbook_type_id = null)
	{
		if($netbook_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE netbook_type_id = ?;", $netbook_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('netbook_type_id' => $line['netbook_type_id'], 'netbook_type_name' => $line['netbook_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($netbook_type_id = null)
	{
		if($netbook_type_id !== null)
		{
			self::Init($netbook_type_id);
		}
		return self::$data;
	}

	public function Set($netbook_type_id = null, $netbook_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($netbook_type_id !== null)
		{
			self::$netbook_type_id = $netbook_type_id;
		}		if($netbook_type_name !== null)
		{
			self::$netbook_type_name = $netbook_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES netbook_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO netbook_type (netbook_type_id, netbook_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE netbook_type_id=VALUES(netbook_type_id),netbook_type_name=VALUES(netbook_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->netbook_type_id, $this->netbook_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>