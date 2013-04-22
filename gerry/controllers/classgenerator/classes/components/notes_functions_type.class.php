<?php
class notes_functions_type
{

	protected static $instance = null;
	private static $table = 'notes_functions_type';
	public static $data = array();
	public static $notes_functions_type_id;
	public static $notes_functions_type_name;
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


	private function Init($notes_functions_type_id = null)
	{
		if($notes_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE notes_functions_type_id = ?;", $notes_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('notes_functions_type_id' => $line['notes_functions_type_id'], 'notes_functions_type_name' => $line['notes_functions_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($notes_functions_type_id = null)
	{
		if($notes_functions_type_id !== null)
		{
			self::Init($notes_functions_type_id);
		}
		return self::$data;
	}

	public function Set($notes_functions_type_id = null, $notes_functions_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($notes_functions_type_id !== null)
		{
			self::$notes_functions_type_id = $notes_functions_type_id;
		}		if($notes_functions_type_name !== null)
		{
			self::$notes_functions_type_name = $notes_functions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES notes_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO notes_functions_type (notes_functions_type_id, notes_functions_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE notes_functions_type_id=VALUES(notes_functions_type_id),notes_functions_type_name=VALUES(notes_functions_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->notes_functions_type_id, $this->notes_functions_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>