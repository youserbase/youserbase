<?php
class netbook_processor_type
{

	protected static $instance = null;
	private static $table = 'netbook_processor_type';
	public static $data = array();
	public static $netbook_processor_type_id;
	public static $netbook_processor_type_name;
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


	private function Init($netbook_processor_type_id = null)
	{
		if($netbook_processor_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE netbook_processor_type_id = ?;", $netbook_processor_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('netbook_processor_type_id' => $line['netbook_processor_type_id'], 'netbook_processor_type_name' => $line['netbook_processor_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($netbook_processor_type_id = null)
	{
		if($netbook_processor_type_id !== null)
		{
			self::Init($netbook_processor_type_id);
		}
		return self::$data;
	}

	public function Set($netbook_processor_type_id = null, $netbook_processor_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($netbook_processor_type_id !== null)
		{
			self::$netbook_processor_type_id = $netbook_processor_type_id;
		}		if($netbook_processor_type_name !== null)
		{
			self::$netbook_processor_type_name = $netbook_processor_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES netbook_processor_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO netbook_processor_type (netbook_processor_type_id, netbook_processor_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE netbook_processor_type_id=VALUES(netbook_processor_type_id),netbook_processor_type_name=VALUES(netbook_processor_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->netbook_processor_type_id, $this->netbook_processor_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>