<?php
class audio_port_type
{

	protected static $instance = null;
	private static $table = 'audio_port_type';
	public static $data = array();
	public static $audio_port_type_id;
	public static $audio_port_type_id_int;
	public static $audio_port_type_name;
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


	private function Init($audio_port_type_id = null)
	{
		if($audio_port_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE audio_port_type_id = ?;", $audio_port_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('audio_port_type_id' => $line['audio_port_type_id'], 'audio_port_type_id_int' => $line['audio_port_type_id_int'], 'audio_port_type_name' => $line['audio_port_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($audio_port_type_id = null)
	{
		if($audio_port_type_id !== null)
		{
			self::Init($audio_port_type_id);
		}
		return self::$data;
	}

	public function Set($audio_port_type_id = null, $audio_port_type_id_int = null, $audio_port_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($audio_port_type_id !== null)
		{
			self::$audio_port_type_id = $audio_port_type_id;
		}		if($audio_port_type_id_int !== null)
		{
			self::$audio_port_type_id_int = $audio_port_type_id_int;
		}		if($audio_port_type_name !== null)
		{
			self::$audio_port_type_name = $audio_port_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES audio_port_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO audio_port_type (audio_port_type_id, audio_port_type_id_int, audio_port_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE audio_port_type_id=VALUES(audio_port_type_id),audio_port_type_id_int=VALUES(audio_port_type_id_int),audio_port_type_name=VALUES(audio_port_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->audio_port_type_id, $this->audio_port_type_id_int, $this->audio_port_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>