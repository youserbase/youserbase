<?php
class body_specials_type
{

	protected static $instance = null;
	private static $table = 'body_specials_type';
	public static $data = array();
	public static $body_specials_type_id;
	public static $body_specials_type_id_int;
	public static $body_specials_type_name;
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


	private function Init($body_specials_type_id = null)
	{
		if($body_specials_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE body_specials_type_id = ?;", $body_specials_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('body_specials_type_id' => $line['body_specials_type_id'], 'body_specials_type_id_int' => $line['body_specials_type_id_int'], 'body_specials_type_name' => $line['body_specials_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($body_specials_type_id = null)
	{
		if($body_specials_type_id !== null)
		{
			self::Init($body_specials_type_id);
		}
		return self::$data;
	}

	public function Set($body_specials_type_id = null, $body_specials_type_id_int = null, $body_specials_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($body_specials_type_id !== null)
		{
			self::$body_specials_type_id = $body_specials_type_id;
		}		if($body_specials_type_id_int !== null)
		{
			self::$body_specials_type_id_int = $body_specials_type_id_int;
		}		if($body_specials_type_name !== null)
		{
			self::$body_specials_type_name = $body_specials_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES body_specials_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO body_specials_type (body_specials_type_id, body_specials_type_id_int, body_specials_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE body_specials_type_id=VALUES(body_specials_type_id),body_specials_type_id_int=VALUES(body_specials_type_id_int),body_specials_type_name=VALUES(body_specials_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->body_specials_type_id, $this->body_specials_type_id_int, $this->body_specials_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>