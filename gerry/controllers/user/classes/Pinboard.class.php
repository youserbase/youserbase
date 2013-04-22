<?php
class Pinboard
{
	public static function Add($youser_id, $sender_id, $message)
	{
		DBManager::Get()->query("INSERT INTO youser_pinboard (youser_id, sender_id, message, timestamp) VALUES (?, ?, ?, NOW())",
			$youser_id,
			$sender_id,
			$message
		);
	}

	public static function DeleteSender($youser_id, $sender_id, $message_id)
	{
		DBManager::Get()->query("UPDATE youser_pinboard SET sender_deleted=NOW() WHERE youser_id=? AND sender_id=? AND message_id=?",
			$youser_id,
			$sender_id,
			$message_id
		);
		return DBManager::Get()->affected_rows()>0;
	}

	public static function Delete($youser_id, $message_id)
	{
		DBManager::Get()->query("DELETE FROM youser_pinboard WHERE youser_id=? AND message_id=?", $youser_id, $message_id);
		return DBManager::Get()->affected_rows()>0;
	}

	public static function Get($youser_id, $show_all, $skip=null, $limit=null)
	{
		$query = $show_all
			? "SELECT message_id, message, sender_id, UNIX_TIMESTAMP(timestamp) AS timestamp FROM youser_pinboard WHERE youser_id=? ORDER BY timestamp DESC"
			:"SELECT message_id, message, sender_id, UNIX_TIMESTAMP(timestamp) AS timestamp FROM youser_pinboard WHERE youser_id=? AND sender_deleted IS NULL ORDER BY timestamp DESC";
		return DBManager::Get()->skip($skip)->limit($limit)->query($query, $youser_id)->to_array();
	}

	public static function GetCount($youser_id, $show_all)
	{
		$query = "SELECT COUNT(*) FROM youser_pinboard WHERE youser_id=?";
		if (!$show_all)
		{
			$query .= ' AND sender_deleted IS NULL';
		}
		return DBManager::Get()->query($query, $youser_id)->fetch_item();
	}
}
?>