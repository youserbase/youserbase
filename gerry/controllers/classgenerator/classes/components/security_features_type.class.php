<?php
class security_features_type
{

	protected static $instance = null;
	private static $table = 'security_features_type';
	public static $data = array();
	public static $security_features_type_id;
	public static $security_features_type_id_int;
	public static $security_features_type_name;
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


	private function Init($security_features_type_id = null)
	{
		if($security_features_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE security_features_type_id = ?;", $security_features_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('security_features_type_id' => $line['security_features_type_id'], 'security_features_type_id_int' => $line['security_features_type_id_int'], 'security_features_type_name' => $line['security_features_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($security_features_type_id = null)
	{
		if($security_features_type_id !== null)
		{
			self::Init($security_features_type_id);
		}
		return self::$data;
	}

	public function Set($security_features_type_id = null, $security_features_type_id_int = null, $security_features_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($security_features_type_id !== null)
		{
			self::$security_features_type_id = $security_features_type_id;
		}		if($security_features_type_id_int !== null)
		{
			self::$security_features_type_id_int = $security_features_type_id_int;
		}		if($security_features_type_name !== null)
		{
			self::$security_features_type_name = $security_features_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES security_features_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO security_features_type (security_features_type_id, security_features_type_id_int, security_features_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE security_features_type_id=VALUES(security_features_type_id),security_features_type_id_int=VALUES(security_features_type_id_int),security_features_type_name=VALUES(security_features_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->security_features_type_id, $this->security_features_type_id_int, $this->security_features_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>