<?php
class market_information_type
{

	protected static $instance = null;
	private static $table = 'market_information_type';
	public static $data = array();
	public static $market_information_type_id;
	public static $market_information_type_id_int;
	public static $market_information_type_name;
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


	private function Init($market_information_type_id = null)
	{
		if($market_information_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE market_information_type_id = ?;", $market_information_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('market_information_type_id' => $line['market_information_type_id'], 'market_information_type_id_int' => $line['market_information_type_id_int'], 'market_information_type_name' => $line['market_information_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($market_information_type_id = null)
	{
		if($market_information_type_id !== null)
		{
			self::Init($market_information_type_id);
		}
		return self::$data;
	}

	public function Set($market_information_type_id = null, $market_information_type_id_int = null, $market_information_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($market_information_type_id !== null)
		{
			self::$market_information_type_id = $market_information_type_id;
		}		if($market_information_type_id_int !== null)
		{
			self::$market_information_type_id_int = $market_information_type_id_int;
		}		if($market_information_type_name !== null)
		{
			self::$market_information_type_name = $market_information_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES market_information_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO market_information_type (market_information_type_id, market_information_type_id_int, market_information_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE market_information_type_id=VALUES(market_information_type_id),market_information_type_id_int=VALUES(market_information_type_id_int),market_information_type_name=VALUES(market_information_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->market_information_type_id, $this->market_information_type_id_int, $this->market_information_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>