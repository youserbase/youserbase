<?php
class radio_antenna_type
{

	protected static $instance = null;
	private static $table = 'radio_antenna_type';
	public static $data = array();
	public static $radio_antenna_type_id;
	public static $radio_antenna_type_name;
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


	private function Init($radio_antenna_type_id = null)
	{
		if($radio_antenna_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE radio_antenna_type_id = ?;", $radio_antenna_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('radio_antenna_type_id' => $line['radio_antenna_type_id'], 'radio_antenna_type_name' => $line['radio_antenna_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($radio_antenna_type_id = null)
	{
		if($radio_antenna_type_id !== null)
		{
			self::Init($radio_antenna_type_id);
		}
		return self::$data;
	}

	public function Set($radio_antenna_type_id = null, $radio_antenna_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($radio_antenna_type_id !== null)
		{
			self::$radio_antenna_type_id = $radio_antenna_type_id;
		}		if($radio_antenna_type_name !== null)
		{
			self::$radio_antenna_type_name = $radio_antenna_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES radio_antenna_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO radio_antenna_type (radio_antenna_type_id, radio_antenna_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE radio_antenna_type_id=VALUES(radio_antenna_type_id),radio_antenna_type_name=VALUES(radio_antenna_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->radio_antenna_type_id, $this->radio_antenna_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>