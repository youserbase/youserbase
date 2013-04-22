<?php
class playlist_functions_type
{

	protected static $instance = null;
	private static $table = 'playlist_functions_type';
	public static $data = array();
	public static $playlist_functions_type_id;
	public static $playlist_functions_type_id_int;
	public static $playlist_functions_type_name;
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


	private function Init($playlist_functions_type_id = null)
	{
		if($playlist_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE playlist_functions_type_id = ?;", $playlist_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('playlist_functions_type_id' => $line['playlist_functions_type_id'], 'playlist_functions_type_id_int' => $line['playlist_functions_type_id_int'], 'playlist_functions_type_name' => $line['playlist_functions_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($playlist_functions_type_id = null)
	{
		if($playlist_functions_type_id !== null)
		{
			self::Init($playlist_functions_type_id);
		}
		return self::$data;
	}

	public function Set($playlist_functions_type_id = null, $playlist_functions_type_id_int = null, $playlist_functions_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($playlist_functions_type_id !== null)
		{
			self::$playlist_functions_type_id = $playlist_functions_type_id;
		}		if($playlist_functions_type_id_int !== null)
		{
			self::$playlist_functions_type_id_int = $playlist_functions_type_id_int;
		}		if($playlist_functions_type_name !== null)
		{
			self::$playlist_functions_type_name = $playlist_functions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES playlist_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO playlist_functions_type (playlist_functions_type_id, playlist_functions_type_id_int, playlist_functions_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE playlist_functions_type_id=VALUES(playlist_functions_type_id),playlist_functions_type_id_int=VALUES(playlist_functions_type_id_int),playlist_functions_type_name=VALUES(playlist_functions_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->playlist_functions_type_id, $this->playlist_functions_type_id_int, $this->playlist_functions_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>