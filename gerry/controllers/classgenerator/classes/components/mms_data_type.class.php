<?php
class mms_data_type
{

	protected static $instance = null;
	private static $table = 'mms_data_type';
	public static $data = array();
	public static $mms_data_type_id;
	public static $mms_data_type_id_int;
	public static $mms_data_type_name;
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


	private function Init($mms_data_type_id = null)
	{
		if($mms_data_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE mms_data_type_id = ?;", $mms_data_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('mms_data_type_id' => $line['mms_data_type_id'], 'mms_data_type_id_int' => $line['mms_data_type_id_int'], 'mms_data_type_name' => $line['mms_data_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($mms_data_type_id = null)
	{
		if($mms_data_type_id !== null)
		{
			self::Init($mms_data_type_id);
		}
		return self::$data;
	}

	public function Set($mms_data_type_id = null, $mms_data_type_id_int = null, $mms_data_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($mms_data_type_id !== null)
		{
			self::$mms_data_type_id = $mms_data_type_id;
		}		if($mms_data_type_id_int !== null)
		{
			self::$mms_data_type_id_int = $mms_data_type_id_int;
		}		if($mms_data_type_name !== null)
		{
			self::$mms_data_type_name = $mms_data_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES mms_data_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO mms_data_type (mms_data_type_id, mms_data_type_id_int, mms_data_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE mms_data_type_id=VALUES(mms_data_type_id),mms_data_type_id_int=VALUES(mms_data_type_id_int),mms_data_type_name=VALUES(mms_data_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->mms_data_type_id, $this->mms_data_type_id_int, $this->mms_data_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>