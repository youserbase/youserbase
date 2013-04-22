<?php
class image_stabilizer_type
{

	protected static $instance = null;
	private static $table = 'image_stabilizer_type';
	public static $data = array();
	public static $image_stabilizer_type_id;
	public static $image_stabilizer_type_id_int;
	public static $image_stabilizer_type_name;
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


	private function Init($image_stabilizer_type_id = null)
	{
		if($image_stabilizer_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE image_stabilizer_type_id = ?;", $image_stabilizer_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('image_stabilizer_type_id' => $line['image_stabilizer_type_id'], 'image_stabilizer_type_id_int' => $line['image_stabilizer_type_id_int'], 'image_stabilizer_type_name' => $line['image_stabilizer_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($image_stabilizer_type_id = null)
	{
		if($image_stabilizer_type_id !== null)
		{
			self::Init($image_stabilizer_type_id);
		}
		return self::$data;
	}

	public function Set($image_stabilizer_type_id = null, $image_stabilizer_type_id_int = null, $image_stabilizer_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($image_stabilizer_type_id !== null)
		{
			self::$image_stabilizer_type_id = $image_stabilizer_type_id;
		}		if($image_stabilizer_type_id_int !== null)
		{
			self::$image_stabilizer_type_id_int = $image_stabilizer_type_id_int;
		}		if($image_stabilizer_type_name !== null)
		{
			self::$image_stabilizer_type_name = $image_stabilizer_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES image_stabilizer_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO image_stabilizer_type (image_stabilizer_type_id, image_stabilizer_type_id_int, image_stabilizer_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE image_stabilizer_type_id=VALUES(image_stabilizer_type_id),image_stabilizer_type_id_int=VALUES(image_stabilizer_type_id_int),image_stabilizer_type_name=VALUES(image_stabilizer_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->image_stabilizer_type_id, $this->image_stabilizer_type_id_int, $this->image_stabilizer_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>