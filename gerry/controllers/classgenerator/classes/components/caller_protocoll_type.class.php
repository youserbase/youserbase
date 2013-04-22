<?php
class caller_protocoll_type
{

	protected static $instance = null;
	private static $table = 'caller_protocoll_type';
	public static $data = array();
	public static $caller_protocoll_type_id;
	public static $caller_protocoll_type_id_int;
	public static $caller_protocoll_type_name;
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


	private function Init($caller_protocoll_type_id = null)
	{
		if($caller_protocoll_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE caller_protocoll_type_id = ?;", $caller_protocoll_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('caller_protocoll_type_id' => $line['caller_protocoll_type_id'], 'caller_protocoll_type_id_int' => $line['caller_protocoll_type_id_int'], 'caller_protocoll_type_name' => $line['caller_protocoll_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($caller_protocoll_type_id = null)
	{
		if($caller_protocoll_type_id !== null)
		{
			self::Init($caller_protocoll_type_id);
		}
		return self::$data;
	}

	public function Set($caller_protocoll_type_id = null, $caller_protocoll_type_id_int = null, $caller_protocoll_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($caller_protocoll_type_id !== null)
		{
			self::$caller_protocoll_type_id = $caller_protocoll_type_id;
		}		if($caller_protocoll_type_id_int !== null)
		{
			self::$caller_protocoll_type_id_int = $caller_protocoll_type_id_int;
		}		if($caller_protocoll_type_name !== null)
		{
			self::$caller_protocoll_type_name = $caller_protocoll_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES caller_protocoll_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO caller_protocoll_type (caller_protocoll_type_id, caller_protocoll_type_id_int, caller_protocoll_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE caller_protocoll_type_id=VALUES(caller_protocoll_type_id),caller_protocoll_type_id_int=VALUES(caller_protocoll_type_id_int),caller_protocoll_type_name=VALUES(caller_protocoll_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->caller_protocoll_type_id, $this->caller_protocoll_type_id_int, $this->caller_protocoll_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>