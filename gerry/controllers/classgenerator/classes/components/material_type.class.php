<?php
class material_type
{

	protected static $instance = null;
	private static $table = 'material_type';
	public static $data = array();
	public static $material_type_id;
	public static $material_type_id_int;
	public static $material_type_name;
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


	private function Init($material_type_id = null)
	{
		if($material_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE material_type_id = ?;", $material_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('material_type_id' => $line['material_type_id'], 'material_type_id_int' => $line['material_type_id_int'], 'material_type_name' => $line['material_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($material_type_id = null)
	{
		if($material_type_id !== null)
		{
			self::Init($material_type_id);
		}
		return self::$data;
	}

	public function Set($material_type_id = null, $material_type_id_int = null, $material_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($material_type_id !== null)
		{
			self::$material_type_id = $material_type_id;
		}		if($material_type_id_int !== null)
		{
			self::$material_type_id_int = $material_type_id_int;
		}		if($material_type_name !== null)
		{
			self::$material_type_name = $material_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES material_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO material_type (material_type_id, material_type_id_int, material_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE material_type_id=VALUES(material_type_id),material_type_id_int=VALUES(material_type_id_int),material_type_name=VALUES(material_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->material_type_id, $this->material_type_id_int, $this->material_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>