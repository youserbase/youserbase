<?php
class device_type_classes
{

	protected static $instance = null;
	private static $table = 'device_type_classes';
	public static $data = array();
	public static $device_type_class;


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


	private function Init($device_type_classes_id = null)
	{
		if($device_type_classes_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE device_type_class = ?;", $device_type_classes_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('device_type_class' => $line['device_type_class'], );
		}
	}

	public function Load($device_type_classes_id = null)
	{
		if($device_type_classes_id !== null)
		{
			self::Init($device_type_classes_id);
		}
		return self::$data;
	}

	public function Set($device_type_class = null)
	{
		if($device_type_class !== null)
		{
			self::$device_type_class = $device_type_class;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES device_type_classes WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO device_type_classes (device_type_class) VALUES(?) ON DUPLICATE KEY UPDATE device_type_class=VALUES(device_type_class);", $this->device_type_class);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>