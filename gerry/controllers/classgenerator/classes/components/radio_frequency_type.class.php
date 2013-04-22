<?php
class radio_frequency_type
{

	protected static $instance = null;
	private static $table = 'radio_frequency_type';
	public static $data = array();
	public static $radio_frequency_type_id;
	public static $radio_frequency_type_id_int;
	public static $radio_frequency_type_name;
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


	private function Init($radio_frequency_type_id = null)
	{
		if($radio_frequency_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE radio_frequency_type_id = ?;", $radio_frequency_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('radio_frequency_type_id' => $line['radio_frequency_type_id'], 'radio_frequency_type_id_int' => $line['radio_frequency_type_id_int'], 'radio_frequency_type_name' => $line['radio_frequency_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($radio_frequency_type_id = null)
	{
		if($radio_frequency_type_id !== null)
		{
			self::Init($radio_frequency_type_id);
		}
		return self::$data;
	}

	public function Set($radio_frequency_type_id = null, $radio_frequency_type_id_int = null, $radio_frequency_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($radio_frequency_type_id !== null)
		{
			self::$radio_frequency_type_id = $radio_frequency_type_id;
		}		if($radio_frequency_type_id_int !== null)
		{
			self::$radio_frequency_type_id_int = $radio_frequency_type_id_int;
		}		if($radio_frequency_type_name !== null)
		{
			self::$radio_frequency_type_name = $radio_frequency_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES radio_frequency_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO radio_frequency_type (radio_frequency_type_id, radio_frequency_type_id_int, radio_frequency_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE radio_frequency_type_id=VALUES(radio_frequency_type_id),radio_frequency_type_id_int=VALUES(radio_frequency_type_id_int),radio_frequency_type_name=VALUES(radio_frequency_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->radio_frequency_type_id, $this->radio_frequency_type_id_int, $this->radio_frequency_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>