<?php
class exposure_modes_type
{

	protected static $instance = null;
	private static $table = 'exposure_modes_type';
	public static $data = array();
	public static $exposure_modes_type_id;
	public static $exposure_modes_type_id_int;
	public static $exposure_modes_type_name;
	public static $exposure_modes_type_shortname;
	public static $timestemp;
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


	private function Init($exposure_modes_type_id = null)
	{
		if($exposure_modes_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE exposure_modes_type_id = ?;", $exposure_modes_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('exposure_modes_type_id' => $line['exposure_modes_type_id'], 'exposure_modes_type_id_int' => $line['exposure_modes_type_id_int'], 'exposure_modes_type_name' => $line['exposure_modes_type_name'], 'exposure_modes_type_shortname' => $line['exposure_modes_type_shortname'], 'timestemp' => $line['timestemp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($exposure_modes_type_id = null)
	{
		if($exposure_modes_type_id !== null)
		{
			self::Init($exposure_modes_type_id);
		}
		return self::$data;
	}

	public function Set($exposure_modes_type_id = null, $exposure_modes_type_id_int = null, $exposure_modes_type_name = null, $exposure_modes_type_shortname = null, $timestemp = null, $youser_id = null)
	{
		if($exposure_modes_type_id !== null)
		{
			self::$exposure_modes_type_id = $exposure_modes_type_id;
		}		if($exposure_modes_type_id_int !== null)
		{
			self::$exposure_modes_type_id_int = $exposure_modes_type_id_int;
		}		if($exposure_modes_type_name !== null)
		{
			self::$exposure_modes_type_name = $exposure_modes_type_name;
		}		if($exposure_modes_type_shortname !== null)
		{
			self::$exposure_modes_type_shortname = $exposure_modes_type_shortname;
		}		if($timestemp !== null)
		{
			self::$timestemp = $timestemp;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES exposure_modes_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO exposure_modes_type (exposure_modes_type_id, exposure_modes_type_id_int, exposure_modes_type_name, exposure_modes_type_shortname, timestemp, youser_id) VALUES(?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE exposure_modes_type_id=VALUES(exposure_modes_type_id),exposure_modes_type_id_int=VALUES(exposure_modes_type_id_int),exposure_modes_type_name=VALUES(exposure_modes_type_name),exposure_modes_type_shortname=VALUES(exposure_modes_type_shortname),timestemp=VALUES(timestemp),youser_id=VALUES(youser_id);", $this->exposure_modes_type_id, $this->exposure_modes_type_id_int, $this->exposure_modes_type_name, $this->exposure_modes_type_shortname, $this->timestemp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>