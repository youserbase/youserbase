<?php
class zoom_type
{

	protected static $instance = null;
	private static $table = 'zoom_type';
	public static $data = array();
	public static $zoom_type_id;
	public static $zoom_type_id_int;
	public static $zoom_type_name;
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


	private function Init($zoom_type_id = null)
	{
		if($zoom_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE zoom_type_id = ?;", $zoom_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('zoom_type_id' => $line['zoom_type_id'], 'zoom_type_id_int' => $line['zoom_type_id_int'], 'zoom_type_name' => $line['zoom_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($zoom_type_id = null)
	{
		if($zoom_type_id !== null)
		{
			self::Init($zoom_type_id);
		}
		return self::$data;
	}

	public function Set($zoom_type_id = null, $zoom_type_id_int = null, $zoom_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($zoom_type_id !== null)
		{
			self::$zoom_type_id = $zoom_type_id;
		}		if($zoom_type_id_int !== null)
		{
			self::$zoom_type_id_int = $zoom_type_id_int;
		}		if($zoom_type_name !== null)
		{
			self::$zoom_type_name = $zoom_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES zoom_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO zoom_type (zoom_type_id, zoom_type_id_int, zoom_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE zoom_type_id=VALUES(zoom_type_id),zoom_type_id_int=VALUES(zoom_type_id_int),zoom_type_name=VALUES(zoom_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->zoom_type_id, $this->zoom_type_id_int, $this->zoom_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>