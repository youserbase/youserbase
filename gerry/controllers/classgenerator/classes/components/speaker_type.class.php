<?php
class speaker_type
{

	protected static $instance = null;
	private static $table = 'speaker_type';
	public static $data = array();
	public static $speaker_type_name;
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


	private function Init($speaker_type_id = null)
	{
		if($speaker_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE speaker_type_name = ?;", $speaker_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('speaker_type_name' => $line['speaker_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($speaker_type_id = null)
	{
		if($speaker_type_id !== null)
		{
			self::Init($speaker_type_id);
		}
		return self::$data;
	}

	public function Set($speaker_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($speaker_type_name !== null)
		{
			self::$speaker_type_name = $speaker_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES speaker_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO speaker_type (speaker_type_name, timestamp, youser_id) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE speaker_type_name=VALUES(speaker_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->speaker_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>