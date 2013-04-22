<?php
class contacts_functions_type
{

	protected static $instance = null;
	private static $table = 'contacts_functions_type';
	public static $data = array();
	public static $contacts_functions_type_id;
	public static $contacts_functions_type_name;
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


	private function Init($contacts_functions_type_id = null)
	{
		if($contacts_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE contacts_functions_type_id = ?;", $contacts_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('contacts_functions_type_id' => $line['contacts_functions_type_id'], 'contacts_functions_type_name' => $line['contacts_functions_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($contacts_functions_type_id = null)
	{
		if($contacts_functions_type_id !== null)
		{
			self::Init($contacts_functions_type_id);
		}
		return self::$data;
	}

	public function Set($contacts_functions_type_id = null, $contacts_functions_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($contacts_functions_type_id !== null)
		{
			self::$contacts_functions_type_id = $contacts_functions_type_id;
		}		if($contacts_functions_type_name !== null)
		{
			self::$contacts_functions_type_name = $contacts_functions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES contacts_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO contacts_functions_type (contacts_functions_type_id, contacts_functions_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE contacts_functions_type_id=VALUES(contacts_functions_type_id),contacts_functions_type_name=VALUES(contacts_functions_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->contacts_functions_type_id, $this->contacts_functions_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>