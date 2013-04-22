<?php
class manufacturer
{

	protected static $instance = null;
	private static $table = 'manufacturer';
	public static $data = array();
	public static $manufacturer_id;
	public static $manufacturer_id_int;
	public static $country_id;
	public static $manufacturer_name;
	public static $manufacturer_alternativename;
	public static $manufacturer_website;
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


	private function Init($manufacturer_id = null)
	{
		if($manufacturer_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE manufacturer_id = ?;", $manufacturer_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('manufacturer_id' => $line['manufacturer_id'], 'manufacturer_id_int' => $line['manufacturer_id_int'], 'country_id' => $line['country_id'], 'manufacturer_name' => $line['manufacturer_name'], 'manufacturer_alternativename' => $line['manufacturer_alternativename'], 'manufacturer_website' => $line['manufacturer_website'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($manufacturer_id = null)
	{
		if($manufacturer_id !== null)
		{
			self::Init($manufacturer_id);
		}
		return self::$data;
	}

	public function Set($manufacturer_id = null, $manufacturer_id_int = null, $country_id = null, $manufacturer_name = null, $manufacturer_alternativename = null, $manufacturer_website = null, $timestamp = null, $youser_id = null)
	{
		if($manufacturer_id !== null)
		{
			self::$manufacturer_id = $manufacturer_id;
		}		if($manufacturer_id_int !== null)
		{
			self::$manufacturer_id_int = $manufacturer_id_int;
		}		if($country_id !== null)
		{
			self::$country_id = $country_id;
		}		if($manufacturer_name !== null)
		{
			self::$manufacturer_name = $manufacturer_name;
		}		if($manufacturer_alternativename !== null)
		{
			self::$manufacturer_alternativename = $manufacturer_alternativename;
		}		if($manufacturer_website !== null)
		{
			self::$manufacturer_website = $manufacturer_website;
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
		DBManager::Get('devices')->query("LOCK TABLES manufacturer WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO manufacturer (manufacturer_id, manufacturer_id_int, country_id, manufacturer_name, manufacturer_alternativename, manufacturer_website, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE manufacturer_id=VALUES(manufacturer_id),manufacturer_id_int=VALUES(manufacturer_id_int),country_id=VALUES(country_id),manufacturer_name=VALUES(manufacturer_name),manufacturer_alternativename=VALUES(manufacturer_alternativename),manufacturer_website=VALUES(manufacturer_website),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->manufacturer_id, $this->manufacturer_id_int, $this->country_id, $this->manufacturer_name, $this->manufacturer_alternativename, $this->manufacturer_website, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>