<?php
class email_protocoll_type
{

	protected static $instance = null;
	private static $table = 'email_protocoll_type';
	public static $data = array();
	public static $email_protocoll_type_id;
	public static $email_protocoll_type_id_int;
	public static $email_protocoll_type_name;
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


	private function Init($email_protocoll_type_id = null)
	{
		if($email_protocoll_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE email_protocoll_type_id = ?;", $email_protocoll_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('email_protocoll_type_id' => $line['email_protocoll_type_id'], 'email_protocoll_type_id_int' => $line['email_protocoll_type_id_int'], 'email_protocoll_type_name' => $line['email_protocoll_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($email_protocoll_type_id = null)
	{
		if($email_protocoll_type_id !== null)
		{
			self::Init($email_protocoll_type_id);
		}
		return self::$data;
	}

	public function Set($email_protocoll_type_id = null, $email_protocoll_type_id_int = null, $email_protocoll_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($email_protocoll_type_id !== null)
		{
			self::$email_protocoll_type_id = $email_protocoll_type_id;
		}		if($email_protocoll_type_id_int !== null)
		{
			self::$email_protocoll_type_id_int = $email_protocoll_type_id_int;
		}		if($email_protocoll_type_name !== null)
		{
			self::$email_protocoll_type_name = $email_protocoll_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES email_protocoll_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO email_protocoll_type (email_protocoll_type_id, email_protocoll_type_id_int, email_protocoll_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE email_protocoll_type_id=VALUES(email_protocoll_type_id),email_protocoll_type_id_int=VALUES(email_protocoll_type_id_int),email_protocoll_type_name=VALUES(email_protocoll_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->email_protocoll_type_id, $this->email_protocoll_type_id_int, $this->email_protocoll_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>