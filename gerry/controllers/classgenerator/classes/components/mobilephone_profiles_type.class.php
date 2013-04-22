<?php
class mobilephone_profiles_type
{

	protected static $instance = null;
	private static $table = 'mobilephone_profiles_type';
	public static $data = array();
	public static $mobilephone_profiles_type_id;
	public static $mobilephone_profiles_type_id_int;
	public static $mobilephone_profiles_type_name;
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


	private function Init($mobilephone_profiles_type_id = null)
	{
		if($mobilephone_profiles_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE mobilephone_profiles_type_id = ?;", $mobilephone_profiles_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('mobilephone_profiles_type_id' => $line['mobilephone_profiles_type_id'], 'mobilephone_profiles_type_id_int' => $line['mobilephone_profiles_type_id_int'], 'mobilephone_profiles_type_name' => $line['mobilephone_profiles_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($mobilephone_profiles_type_id = null)
	{
		if($mobilephone_profiles_type_id !== null)
		{
			self::Init($mobilephone_profiles_type_id);
		}
		return self::$data;
	}

	public function Set($mobilephone_profiles_type_id = null, $mobilephone_profiles_type_id_int = null, $mobilephone_profiles_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($mobilephone_profiles_type_id !== null)
		{
			self::$mobilephone_profiles_type_id = $mobilephone_profiles_type_id;
		}		if($mobilephone_profiles_type_id_int !== null)
		{
			self::$mobilephone_profiles_type_id_int = $mobilephone_profiles_type_id_int;
		}		if($mobilephone_profiles_type_name !== null)
		{
			self::$mobilephone_profiles_type_name = $mobilephone_profiles_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES mobilephone_profiles_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO mobilephone_profiles_type (mobilephone_profiles_type_id, mobilephone_profiles_type_id_int, mobilephone_profiles_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE mobilephone_profiles_type_id=VALUES(mobilephone_profiles_type_id),mobilephone_profiles_type_id_int=VALUES(mobilephone_profiles_type_id_int),mobilephone_profiles_type_name=VALUES(mobilephone_profiles_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->mobilephone_profiles_type_id, $this->mobilephone_profiles_type_id_int, $this->mobilephone_profiles_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>