<?php
class Activity
{
	public static function Log($activity, $object_id, $subject_id=null, $scope=null)
	{
		DBManager::Get()->query("INSERT INTO activities (timestamp, activity, object_id, subject_id, scope) VALUES (NOW(),?,?,?,?)",
			$activity,
			$object_id,
			$subject_id,
			$scope
		);
	}

	public static function Clear($activity, $object_id, $subject_id=false, $scope=false)
	{
		$query = "DELETE FROM activities WHERE activity=? AND object_id=?";
		if ($subject_id!==false)
		{
			$query .= ' AND subject_id=?';
		}
		if ($scope!==false)
		{
			$query .= ' AND scope=?';
		}

		DBManager::Get()->query($query,
			$activity,
			$object_id,
			$subject_id,
			$scope
		);
	}

	public static function Retrieve($limit = null, $skip = null)
	{
		return DBManager::Get()->skip($skip)->limit($limit)->query("SELECT activity, object_id, subject_id, scope, UNIX_TIMESTAMP(timestamp) AS timestamp FROM activities ORDER BY timestamp DESC")->to_array();
	}

	public static function Get($object_id, $limit=null, $skip = null)
	{
		return DBManager::Get()->skip($skip)->limit($limit)->query("SELECT activity, object_id, subject_id, scope, UNIX_TIMESTAMP(timestamp) AS timestamp FROM activities WHERE (object_id IN (SELECT IF(youser_id_a=?, youser_id_b, youser_id_a) FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND confirmed IS NOT NULL) OR subject_id IN (SELECT IF(youser_id_a=?, youser_id_b, youser_id_a) FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND confirmed IS NOT NULL)) AND NOT object_id=? AND NOT subject_id=? ORDER BY timestamp DESC", $object_id, $object_id, $object_id, $object_id, $object_id, $object_id)->to_array();
	}

	public static function GetNewSince($object_id, $timestamp)
	{
		return DBManager::Get()->query("SELECT COUNT(*) FROM activities WHERE (object_id IN (SELECT IF(youser_id_a=?, youser_id_b, youser_id_a) FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND confirmed IS NOT NULL) OR subject_id IN (SELECT IF(youser_id_a=?, youser_id_b, youser_id_a) FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND confirmed IS NOT NULL)) AND NOT object_id=? AND NOT subject_id=? AND timestamp>FROM_UNIXTIME(IFNULL(?, NOW()))", $object_id, $object_id, $object_id, $object_id, $object_id, $object_id, $timestamp)->fetch_item();
	}

	public static function CollectGarbage($minutes=40320)
	{
		$devices = DBManager::Get('devices')->query("SELECT device_id FROM device")->to_array(null, 'device_id');
		DBManager::Get()->query("DELETE FROM activities WHERE activity LIKE 'Device%' AND subject_id NOT IN (?)", $devices);

		DBManager::Get()->query("DELETE FROM activities WHERE timestamp<TIMESTAMPADD(MINUTE, -?, NOW())",
			$minutes
		);
		return DBManager::Get()->affected_rows();
	}
}
?>