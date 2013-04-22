<?php
class document_format_type
{

	protected static $instance = null;
	private static $table = 'document_format_type';
	public static $data = array();
	public static $document_format_type_id;
	public static $document_format_type_id_int;
	public static $document_format_type_name;
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


	private function Init($document_format_type_id = null)
	{
		if($document_format_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE document_format_type_id = ?;", $document_format_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('document_format_type_id' => $line['document_format_type_id'], 'document_format_type_id_int' => $line['document_format_type_id_int'], 'document_format_type_name' => $line['document_format_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($document_format_type_id = null)
	{
		if($document_format_type_id !== null)
		{
			self::Init($document_format_type_id);
		}
		return self::$data;
	}

	public function Set($document_format_type_id = null, $document_format_type_id_int = null, $document_format_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($document_format_type_id !== null)
		{
			self::$document_format_type_id = $document_format_type_id;
		}		if($document_format_type_id_int !== null)
		{
			self::$document_format_type_id_int = $document_format_type_id_int;
		}		if($document_format_type_name !== null)
		{
			self::$document_format_type_name = $document_format_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES document_format_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO document_format_type (document_format_type_id, document_format_type_id_int, document_format_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE document_format_type_id=VALUES(document_format_type_id),document_format_type_id_int=VALUES(document_format_type_id_int),document_format_type_name=VALUES(document_format_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->document_format_type_id, $this->document_format_type_id_int, $this->document_format_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>