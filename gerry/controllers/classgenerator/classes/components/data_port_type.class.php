<?php
class data_port_type
{

	protected static $instance = null;
	private static $table = 'data_port_type';
	public static $data = array();
	public static $data_port_type_id;
	public static $data_port_type_id_int;
	public static $data_port_type_name;
	public static $data_port_type_standard;
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


	private function Init($data_port_type_id = null)
	{
		if($data_port_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE data_port_type_id = ?;", $data_port_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('data_port_type_id' => $line['data_port_type_id'], 'data_port_type_id_int' => $line['data_port_type_id_int'], 'data_port_type_name' => $line['data_port_type_name'], 'data_port_type_standard' => $line['data_port_type_standard'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($data_port_type_id = null)
	{
		if($data_port_type_id !== null)
		{
			self::Init($data_port_type_id);
		}
		return self::$data;
	}

	public function Set($data_port_type_id = null, $data_port_type_id_int = null, $data_port_type_name = null, $data_port_type_standard = null, $timestamp = null, $youser_id = null)
	{
		if($data_port_type_id !== null)
		{
			self::$data_port_type_id = $data_port_type_id;
		}		if($data_port_type_id_int !== null)
		{
			self::$data_port_type_id_int = $data_port_type_id_int;
		}		if($data_port_type_name !== null)
		{
			self::$data_port_type_name = $data_port_type_name;
		}		if($data_port_type_standard !== null)
		{
			self::$data_port_type_standard = $data_port_type_standard;
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
		DBManager::Get('devices')->query("LOCK TABLES data_port_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO data_port_type (data_port_type_id, data_port_type_id_int, data_port_type_name, data_port_type_standard, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE data_port_type_id=VALUES(data_port_type_id),data_port_type_id_int=VALUES(data_port_type_id_int),data_port_type_name=VALUES(data_port_type_name),data_port_type_standard=VALUES(data_port_type_standard),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->data_port_type_id, $this->data_port_type_id_int, $this->data_port_type_name, $this->data_port_type_standard, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>