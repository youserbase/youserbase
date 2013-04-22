<?php
class currency_type
{

	protected static $instance = null;
	private static $table = 'currency_type';
	public static $data = array();
	public static $currency_type_id;
	public static $currency_type_name;
	public static $currency_type_short_name;
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


	private function Init($currency_type_id = null)
	{
		if($currency_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE currency_type_id = ?;", $currency_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('currency_type_id' => $line['currency_type_id'], 'currency_type_name' => $line['currency_type_name'], 'currency_type_short_name' => $line['currency_type_short_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($currency_type_id = null)
	{
		if($currency_type_id !== null)
		{
			self::Init($currency_type_id);
		}
		return self::$data;
	}

	public function Set($currency_type_id = null, $currency_type_name = null, $currency_type_short_name = null, $youser_id = null, $timestamp = null)
	{
		if($currency_type_id !== null)
		{
			self::$currency_type_id = $currency_type_id;
		}		if($currency_type_name !== null)
		{
			self::$currency_type_name = $currency_type_name;
		}		if($currency_type_short_name !== null)
		{
			self::$currency_type_short_name = $currency_type_short_name;
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
		DBManager::Get('devices')->query("LOCK TABLES currency_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO currency_type (currency_type_id, currency_type_name, currency_type_short_name, youser_id, timestamp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE currency_type_id=VALUES(currency_type_id),currency_type_name=VALUES(currency_type_name),currency_type_short_name=VALUES(currency_type_short_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->currency_type_id, $this->currency_type_name, $this->currency_type_short_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>