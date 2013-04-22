<?php
class accessories_type
{

	protected static $instance = null;
	private static $table = 'accessories_type';
	public static $data = array();
	public static $accessories_type_id;
	public static $accessories_type_id_int;
	public static $accessories_type_name;
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


	private function Init($accessories_type_id = null)
	{
		if($accessories_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE accessories_type_id = ?;", $accessories_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('accessories_type_id' => $line['accessories_type_id'], 'accessories_type_id_int' => $line['accessories_type_id_int'], 'accessories_type_name' => $line['accessories_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($accessories_type_id = null)
	{
		if($accessories_type_id !== null)
		{
			self::Init($accessories_type_id);
		}
		return self::$data;
	}

	public function Set($accessories_type_id = null, $accessories_type_id_int = null, $accessories_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($accessories_type_id !== null)
		{
			self::$accessories_type_id = $accessories_type_id;
		}		if($accessories_type_id_int !== null)
		{
			self::$accessories_type_id_int = $accessories_type_id_int;
		}		if($accessories_type_name !== null)
		{
			self::$accessories_type_name = $accessories_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES accessories_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO accessories_type (accessories_type_id, accessories_type_id_int, accessories_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE accessories_type_id=VALUES(accessories_type_id),accessories_type_id_int=VALUES(accessories_type_id_int),accessories_type_name=VALUES(accessories_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->accessories_type_id, $this->accessories_type_id_int, $this->accessories_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>