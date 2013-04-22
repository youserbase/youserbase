<?php
class voice_codec_type
{

	protected static $instance = null;
	private static $table = 'voice_codec_type';
	public static $data = array();
	public static $voice_codec_type_id;
	public static $voice_codec_type_id_int;
	public static $voice_codec_type_name;
	public static $voice_codec_type_shortname;
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


	private function Init($voice_codec_type_id = null)
	{
		if($voice_codec_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE voice_codec_type_id = ?;", $voice_codec_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('voice_codec_type_id' => $line['voice_codec_type_id'], 'voice_codec_type_id_int' => $line['voice_codec_type_id_int'], 'voice_codec_type_name' => $line['voice_codec_type_name'], 'voice_codec_type_shortname' => $line['voice_codec_type_shortname'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($voice_codec_type_id = null)
	{
		if($voice_codec_type_id !== null)
		{
			self::Init($voice_codec_type_id);
		}
		return self::$data;
	}

	public function Set($voice_codec_type_id = null, $voice_codec_type_id_int = null, $voice_codec_type_name = null, $voice_codec_type_shortname = null, $timestamp = null, $youser_id = null)
	{
		if($voice_codec_type_id !== null)
		{
			self::$voice_codec_type_id = $voice_codec_type_id;
		}		if($voice_codec_type_id_int !== null)
		{
			self::$voice_codec_type_id_int = $voice_codec_type_id_int;
		}		if($voice_codec_type_name !== null)
		{
			self::$voice_codec_type_name = $voice_codec_type_name;
		}		if($voice_codec_type_shortname !== null)
		{
			self::$voice_codec_type_shortname = $voice_codec_type_shortname;
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
		DBManager::Get('devices')->query("LOCK TABLES voice_codec_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO voice_codec_type (voice_codec_type_id, voice_codec_type_id_int, voice_codec_type_name, voice_codec_type_shortname, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE voice_codec_type_id=VALUES(voice_codec_type_id),voice_codec_type_id_int=VALUES(voice_codec_type_id_int),voice_codec_type_name=VALUES(voice_codec_type_name),voice_codec_type_shortname=VALUES(voice_codec_type_shortname),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->voice_codec_type_id, $this->voice_codec_type_id_int, $this->voice_codec_type_name, $this->voice_codec_type_shortname, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>