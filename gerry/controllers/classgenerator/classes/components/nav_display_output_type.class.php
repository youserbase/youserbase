<?php
class nav_display_output_type
{

	protected static $instance = null;
	private static $table = 'nav_display_output_type';
	public static $data = array();
	public static $nav_display_output_type_id;
	public static $nav_display_output_type_name;
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


	private function Init($nav_display_output_type_id = null)
	{
		if($nav_display_output_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE nav_display_output_type_id = ?;", $nav_display_output_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('nav_display_output_type_id' => $line['nav_display_output_type_id'], 'nav_display_output_type_name' => $line['nav_display_output_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($nav_display_output_type_id = null)
	{
		if($nav_display_output_type_id !== null)
		{
			self::Init($nav_display_output_type_id);
		}
		return self::$data;
	}

	public function Set($nav_display_output_type_id = null, $nav_display_output_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($nav_display_output_type_id !== null)
		{
			self::$nav_display_output_type_id = $nav_display_output_type_id;
		}		if($nav_display_output_type_name !== null)
		{
			self::$nav_display_output_type_name = $nav_display_output_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES nav_display_output_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO nav_display_output_type (nav_display_output_type_id, nav_display_output_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE nav_display_output_type_id=VALUES(nav_display_output_type_id),nav_display_output_type_name=VALUES(nav_display_output_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->nav_display_output_type_id, $this->nav_display_output_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>