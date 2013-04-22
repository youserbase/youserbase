<?php
class email_functions_type
{

	protected static $instance = null;
	private static $table = 'email_functions_type';
	public static $data = array();
	public static $email_functions_type_id;
	public static $email_functions_type_id_int;
	public static $email_functions_type_name;
	public static $email_functions_type_shortname;
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


	private function Init($email_functions_type_id = null)
	{
		if($email_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE email_functions_type_id = ?;", $email_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('email_functions_type_id' => $line['email_functions_type_id'], 'email_functions_type_id_int' => $line['email_functions_type_id_int'], 'email_functions_type_name' => $line['email_functions_type_name'], 'email_functions_type_shortname' => $line['email_functions_type_shortname'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($email_functions_type_id = null)
	{
		if($email_functions_type_id !== null)
		{
			self::Init($email_functions_type_id);
		}
		return self::$data;
	}

	public function Set($email_functions_type_id = null, $email_functions_type_id_int = null, $email_functions_type_name = null, $email_functions_type_shortname = null, $timestamp = null, $youser_id = null)
	{
		if($email_functions_type_id !== null)
		{
			self::$email_functions_type_id = $email_functions_type_id;
		}		if($email_functions_type_id_int !== null)
		{
			self::$email_functions_type_id_int = $email_functions_type_id_int;
		}		if($email_functions_type_name !== null)
		{
			self::$email_functions_type_name = $email_functions_type_name;
		}		if($email_functions_type_shortname !== null)
		{
			self::$email_functions_type_shortname = $email_functions_type_shortname;
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
		DBManager::Get('devices')->query("LOCK TABLES email_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO email_functions_type (email_functions_type_id, email_functions_type_id_int, email_functions_type_name, email_functions_type_shortname, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE email_functions_type_id=VALUES(email_functions_type_id),email_functions_type_id_int=VALUES(email_functions_type_id_int),email_functions_type_name=VALUES(email_functions_type_name),email_functions_type_shortname=VALUES(email_functions_type_shortname),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->email_functions_type_id, $this->email_functions_type_id_int, $this->email_functions_type_name, $this->email_functions_type_shortname, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>