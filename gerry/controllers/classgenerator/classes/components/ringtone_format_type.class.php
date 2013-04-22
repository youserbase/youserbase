<?php
class ringtone_format_type
{

	protected static $instance = null;
	private static $table = 'ringtone_format_type';
	public static $data = array();
	public static $ringtone_format_type_id;
	public static $ringtone_format_type_id_int;
	public static $ringtone_format_type_name;
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


	private function Init($ringtone_format_type_id = null)
	{
		if($ringtone_format_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE ringtone_format_type_id = ?;", $ringtone_format_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('ringtone_format_type_id' => $line['ringtone_format_type_id'], 'ringtone_format_type_id_int' => $line['ringtone_format_type_id_int'], 'ringtone_format_type_name' => $line['ringtone_format_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($ringtone_format_type_id = null)
	{
		if($ringtone_format_type_id !== null)
		{
			self::Init($ringtone_format_type_id);
		}
		return self::$data;
	}

	public function Set($ringtone_format_type_id = null, $ringtone_format_type_id_int = null, $ringtone_format_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($ringtone_format_type_id !== null)
		{
			self::$ringtone_format_type_id = $ringtone_format_type_id;
		}		if($ringtone_format_type_id_int !== null)
		{
			self::$ringtone_format_type_id_int = $ringtone_format_type_id_int;
		}		if($ringtone_format_type_name !== null)
		{
			self::$ringtone_format_type_name = $ringtone_format_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES ringtone_format_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO ringtone_format_type (ringtone_format_type_id, ringtone_format_type_id_int, ringtone_format_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE ringtone_format_type_id=VALUES(ringtone_format_type_id),ringtone_format_type_id_int=VALUES(ringtone_format_type_id_int),ringtone_format_type_name=VALUES(ringtone_format_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->ringtone_format_type_id, $this->ringtone_format_type_id_int, $this->ringtone_format_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>