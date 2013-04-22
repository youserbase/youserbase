<?php
class screensaver_type
{

	protected static $instance = null;
	private static $table = 'screensaver_type';
	public static $data = array();
	public static $screensaver_type_id;
	public static $screensaver_type_id_int;
	public static $screensaver_type_name;
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


	private function Init($screensaver_type_id = null)
	{
		if($screensaver_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE screensaver_type_id = ?;", $screensaver_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('screensaver_type_id' => $line['screensaver_type_id'], 'screensaver_type_id_int' => $line['screensaver_type_id_int'], 'screensaver_type_name' => $line['screensaver_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($screensaver_type_id = null)
	{
		if($screensaver_type_id !== null)
		{
			self::Init($screensaver_type_id);
		}
		return self::$data;
	}

	public function Set($screensaver_type_id = null, $screensaver_type_id_int = null, $screensaver_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($screensaver_type_id !== null)
		{
			self::$screensaver_type_id = $screensaver_type_id;
		}		if($screensaver_type_id_int !== null)
		{
			self::$screensaver_type_id_int = $screensaver_type_id_int;
		}		if($screensaver_type_name !== null)
		{
			self::$screensaver_type_name = $screensaver_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES screensaver_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO screensaver_type (screensaver_type_id, screensaver_type_id_int, screensaver_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE screensaver_type_id=VALUES(screensaver_type_id),screensaver_type_id_int=VALUES(screensaver_type_id_int),screensaver_type_name=VALUES(screensaver_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->screensaver_type_id, $this->screensaver_type_id_int, $this->screensaver_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>