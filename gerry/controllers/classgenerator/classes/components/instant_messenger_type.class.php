<?php
class instant_messenger_type
{

	protected static $instance = null;
	private static $table = 'instant_messenger_type';
	public static $data = array();
	public static $instant_messenger_type_id;
	public static $instant_messenger_type_id_int;
	public static $instant_messenger_type_name;
	public static $youser_id;
	public static $timestamp;


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


	private function Init($instant_messenger_type_id = null)
	{
		if($instant_messenger_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE instant_messenger_type_id = ?;", $instant_messenger_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('instant_messenger_type_id' => $line['instant_messenger_type_id'], 'instant_messenger_type_id_int' => $line['instant_messenger_type_id_int'], 'instant_messenger_type_name' => $line['instant_messenger_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($instant_messenger_type_id = null)
	{
		if($instant_messenger_type_id !== null)
		{
			self::Init($instant_messenger_type_id);
		}
		return self::$data;
	}

	public function Set($instant_messenger_type_id = null, $instant_messenger_type_id_int = null, $instant_messenger_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($instant_messenger_type_id !== null)
		{
			self::$instant_messenger_type_id = $instant_messenger_type_id;
		}		if($instant_messenger_type_id_int !== null)
		{
			self::$instant_messenger_type_id_int = $instant_messenger_type_id_int;
		}		if($instant_messenger_type_name !== null)
		{
			self::$instant_messenger_type_name = $instant_messenger_type_name;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES instant_messenger_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO instant_messenger_type (instant_messenger_type_id, instant_messenger_type_id_int, instant_messenger_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE instant_messenger_type_id=VALUES(instant_messenger_type_id),instant_messenger_type_id_int=VALUES(instant_messenger_type_id_int),instant_messenger_type_name=VALUES(instant_messenger_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->instant_messenger_type_id, $this->instant_messenger_type_id_int, $this->instant_messenger_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>