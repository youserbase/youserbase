<?php
class data_network_type
{

	protected static $instance = null;
	private static $table = 'data_network_type';
	public static $data = array();
	public static $data_network_type_id;
	public static $data_network_type_id_int;
	public static $data_network_type_name;
	public static $data_network_type_standard;
	public static $bandwidth_up_mbit;
	public static $bandwidth_down_mbit;
	public static $range;
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


	private function Init($data_network_type_id = null)
	{
		if($data_network_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE data_network_type_id = ?;", $data_network_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('data_network_type_id' => $line['data_network_type_id'], 'data_network_type_id_int' => $line['data_network_type_id_int'], 'data_network_type_name' => $line['data_network_type_name'], 'data_network_type_standard' => $line['data_network_type_standard'], 'bandwidth_up_mbit' => $line['bandwidth_up_mbit'], 'bandwidth_down_mbit' => $line['bandwidth_down_mbit'], 'range' => $line['range'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($data_network_type_id = null)
	{
		if($data_network_type_id !== null)
		{
			self::Init($data_network_type_id);
		}
		return self::$data;
	}

	public function Set($data_network_type_id = null, $data_network_type_id_int = null, $data_network_type_name = null, $data_network_type_standard = null, $bandwidth_up_mbit = null, $bandwidth_down_mbit = null, $range = null, $timestamp = null, $youser_id = null)
	{
		if($data_network_type_id !== null)
		{
			self::$data_network_type_id = $data_network_type_id;
		}		if($data_network_type_id_int !== null)
		{
			self::$data_network_type_id_int = $data_network_type_id_int;
		}		if($data_network_type_name !== null)
		{
			self::$data_network_type_name = $data_network_type_name;
		}		if($data_network_type_standard !== null)
		{
			self::$data_network_type_standard = $data_network_type_standard;
		}		if($bandwidth_up_mbit !== null)
		{
			self::$bandwidth_up_mbit = $bandwidth_up_mbit;
		}		if($bandwidth_down_mbit !== null)
		{
			self::$bandwidth_down_mbit = $bandwidth_down_mbit;
		}		if($range !== null)
		{
			self::$range = $range;
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
		DBManager::Get('devices')->query("LOCK TABLES data_network_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO data_network_type (data_network_type_id, data_network_type_id_int, data_network_type_name, data_network_type_standard, bandwidth_up_mbit, bandwidth_down_mbit, range, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE data_network_type_id=VALUES(data_network_type_id),data_network_type_id_int=VALUES(data_network_type_id_int),data_network_type_name=VALUES(data_network_type_name),data_network_type_standard=VALUES(data_network_type_standard),bandwidth_up_mbit=VALUES(bandwidth_up_mbit),bandwidth_down_mbit=VALUES(bandwidth_down_mbit),range=VALUES(range),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->data_network_type_id, $this->data_network_type_id_int, $this->data_network_type_name, $this->data_network_type_standard, $this->bandwidth_up_mbit, $this->bandwidth_down_mbit, $this->range, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>