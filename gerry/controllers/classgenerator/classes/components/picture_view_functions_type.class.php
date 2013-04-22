<?php
class picture_view_functions_type
{

	protected static $instance = null;
	private static $table = 'picture_view_functions_type';
	public static $data = array();
	public static $picture_view_functions_type_id;
	public static $picture_view_functions_type_id_int;
	public static $picture_view_functions_type_name;
	public static $youser_id;
	public static $timestemp;


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


	private function Init($picture_view_functions_type_id = null)
	{
		if($picture_view_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE picture_view_functions_type_id = ?;", $picture_view_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('picture_view_functions_type_id' => $line['picture_view_functions_type_id'], 'picture_view_functions_type_id_int' => $line['picture_view_functions_type_id_int'], 'picture_view_functions_type_name' => $line['picture_view_functions_type_name'], 'youser_id' => $line['youser_id'], 'timestemp' => $line['timestemp'], );
		}
	}

	public function Load($picture_view_functions_type_id = null)
	{
		if($picture_view_functions_type_id !== null)
		{
			self::Init($picture_view_functions_type_id);
		}
		return self::$data;
	}

	public function Set($picture_view_functions_type_id = null, $picture_view_functions_type_id_int = null, $picture_view_functions_type_name = null, $youser_id = null, $timestemp = null)
	{
		if($picture_view_functions_type_id !== null)
		{
			self::$picture_view_functions_type_id = $picture_view_functions_type_id;
		}		if($picture_view_functions_type_id_int !== null)
		{
			self::$picture_view_functions_type_id_int = $picture_view_functions_type_id_int;
		}		if($picture_view_functions_type_name !== null)
		{
			self::$picture_view_functions_type_name = $picture_view_functions_type_name;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}		if($timestemp !== null)
		{
			self::$timestemp = $timestemp;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES picture_view_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO picture_view_functions_type (picture_view_functions_type_id, picture_view_functions_type_id_int, picture_view_functions_type_name, youser_id, timestemp) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE picture_view_functions_type_id=VALUES(picture_view_functions_type_id),picture_view_functions_type_id_int=VALUES(picture_view_functions_type_id_int),picture_view_functions_type_name=VALUES(picture_view_functions_type_name),youser_id=VALUES(youser_id),timestemp=VALUES(timestemp);", $this->picture_view_functions_type_id, $this->picture_view_functions_type_id_int, $this->picture_view_functions_type_name, $this->youser_id, $this->timestemp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>