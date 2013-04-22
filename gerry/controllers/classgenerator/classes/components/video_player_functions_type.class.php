<?php
class video_player_functions_type
{

	protected static $instance = null;
	private static $table = 'video_player_functions_type';
	public static $data = array();
	public static $video_player_functions_type_id;
	public static $video_player_functions_type_id_int;
	public static $video_player_functions_type_name;
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


	private function Init($video_player_functions_type_id = null)
	{
		if($video_player_functions_type_id !== null)
		{
			$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." WHERE video_player_functions_type_id = ?;", $video_player_functions_type_id)->to_array();
			self::$data = array();
		}
		else{
		$result = DBManager::Get('devices')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();
		}
		foreach($result as $id => $line)
		{
			self::$data[$id] = array('video_player_functions_type_id' => $line['video_player_functions_type_id'], 'video_player_functions_type_id_int' => $line['video_player_functions_type_id_int'], 'video_player_functions_type_name' => $line['video_player_functions_type_name'], 'timestamp' => $line['timestamp'], 'youser_id' => $line['youser_id'], );
		}
	}

	public function Load($video_player_functions_type_id = null)
	{
		if($video_player_functions_type_id !== null)
		{
			self::Init($video_player_functions_type_id);
		}
		return self::$data;
	}

	public function Set($video_player_functions_type_id = null, $video_player_functions_type_id_int = null, $video_player_functions_type_name = null, $timestamp = null, $youser_id = null)
	{
		if($video_player_functions_type_id !== null)
		{
			self::$video_player_functions_type_id = $video_player_functions_type_id;
		}		if($video_player_functions_type_id_int !== null)
		{
			self::$video_player_functions_type_id_int = $video_player_functions_type_id_int;
		}		if($video_player_functions_type_name !== null)
		{
			self::$video_player_functions_type_name = $video_player_functions_type_name;
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
		DBManager::Get('devices')->query("LOCK TABLES video_player_functions_type WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO video_player_functions_type (video_player_functions_type_id, video_player_functions_type_id_int, video_player_functions_type_name, timestamp, youser_id) VALUES(?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE video_player_functions_type_id=VALUES(video_player_functions_type_id),video_player_functions_type_id_int=VALUES(video_player_functions_type_id_int),video_player_functions_type_name=VALUES(video_player_functions_type_name),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->video_player_functions_type_id, $this->video_player_functions_type_id_int, $this->video_player_functions_type_name, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");
	}

}
?>