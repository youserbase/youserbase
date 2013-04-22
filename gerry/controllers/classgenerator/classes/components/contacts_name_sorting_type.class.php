<?php
class contacts_name_sorting_type
{

	protected static $instance = null;
	private static $table = 'contacts_name_sorting_type';
	public static $data = array();
	public static $contacts_name_sorting_type_id;
	public static $contacts_name_sorting_type_name;
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


	private function Init($contacts_name_sorting_type_id = null)
	{
		if($contacts_name_sorting_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE contacts_name_sorting_type_id = ?;", $contacts_name_sorting_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('contacts_name_sorting_type_id' => $line['contacts_name_sorting_type_id'], 'contacts_name_sorting_type_name' => $line['contacts_name_sorting_type_name'], 'youser_id' => $line['youser_id'], 'timestamp' => $line['timestamp'], );
		}
	}

	public function Load($contacts_name_sorting_type_id = null)
	{
		if($contacts_name_sorting_type_id !== null)
		{
			self::Init($contacts_name_sorting_type_id);
		}
		return self::$data;
	}

	public function Set($contacts_name_sorting_type_id = null, $contacts_name_sorting_type_name = null, $youser_id = null, $timestamp = null)
	{
		if($contacts_name_sorting_type_id !== null)
		{
			self::$contacts_name_sorting_type_id = $contacts_name_sorting_type_id;
		}		if($contacts_name_sorting_type_name !== null)
		{
			self::$contacts_name_sorting_type_name = $contacts_name_sorting_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES contacts_name_sorting_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO contacts_name_sorting_type (contacts_name_sorting_type_id, contacts_name_sorting_type_name, youser_id, timestamp) VALUES(?, ?, ?, ?) ON DUPLICATE KEY UPDATE contacts_name_sorting_type_id=VALUES(contacts_name_sorting_type_id),contacts_name_sorting_type_name=VALUES(contacts_name_sorting_type_name),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->contacts_name_sorting_type_id, $this->contacts_name_sorting_type_name, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>