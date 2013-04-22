<?php
class finder_type
{

	protected static $instance = null;
	private static $table = 'finder_type';
	public static $data = array();
	public static $finder_type_id;
	public static $finder_type_id_int;
	public static $finder_type_name;
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


	private function Init($finder_type_id = null)
	{
		if($finder_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE finder_type_id = ?;", $finder_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('finder_type_id' => $line['finder_type_id'], 'finder_type_id_int' => $line['finder_type_id_int'], 'finder_type_name' => $line['finder_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($finder_type_id = null)
	{
		if($finder_type_id !== null)
		{
			self::Init($finder_type_id);
		}
		return self::$data;
	}

	public function Set($finder_type_id = null, $finder_type_id_int = null, $finder_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($finder_type_id !== null)
		{
			self::$finder_type_id = $finder_type_id;
		}		if($finder_type_id_int !== null)
		{
			self::$finder_type_id_int = $finder_type_id_int;
		}		if($finder_type_name !== null)
		{
			self::$finder_type_name = $finder_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES finder_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO finder_type (finder_type_id, finder_type_id_int, finder_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE finder_type_id=VALUES(finder_type_id),finder_type_id_int=VALUES(finder_type_id_int),finder_type_name=VALUES(finder_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->finder_type_id, $this->finder_type_id_int, $this->finder_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>