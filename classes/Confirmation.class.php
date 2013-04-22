<?php
class Confirmation
{
	public static function Confirm($confirmation_key)
	{
		list($identification, $confirmation_key) = array_slice(explode('-', $confirmation_key.'-'), 0, 2);

		$result = array();

		$result = DBManager::Get()->query("SELECT youser_id, subject, scope, confirmation_key=? AS confirmed FROM confirmations WHERE SUBSTR(hash, -16)=? AND (valid_till IS NULL OR valid_till > NOW())",
			$confirmation_key,
			$identification
		)->fetch_item(array('youser_id', 'subject', 'scope', 'confirmed'));

		DBManager::Get()->query("DELETE FROM confirmations WHERE confirmation_key=? LIMIT 1", $confirmation_key);

		return $result;
	}

	public static function Confirmed($scope, $youser_id, $subject=null)
	{
		return DBManager::Get()->query("SELECT 1 FROM confirmations WHERE youser_id=? AND scope=? AND subject=?",
			$youser_id,
			$scope,
			$subject
		)->is_empty();
	}

	public static function Request($scope, $youser_id, $subject=null, $generate_key=true)
	{
		return self::TimedRequest($scope, $youser_id, null, $subject, $generate_key);
	}

	public static function TimedRequest($scope, $youser_id, $valid_time, $subject=null, $generate_key=true)
	{
		$hash = md5($youser_id.','.$scope.','.$subject);

		$confirmation_key = substr(md5(uniqid($scope, true)), -16);
		if (!$generate_key and !DBManager::Get()->query("SELECT 1 FROM confirmations WHERE hash=?", $hash)->is_empty())
		{
			$confirmation_key = DBManager::Get()->query("SELECT confirmation_key FROM confirmations WHERE hash=?", $hash)->fetch_item();
		}


		DBManager::Get()->query("INSERT INTO confirmations (youser_id, scope, confirmation_key, subject, timestamp, valid_till, hash) VALUES (?, ?, ?, ?, NOW(), TIMESTAMPADD(SECOND, ?, NOW()), ?) ON DUPLICATE KEY UPDATE confirmation_key=VALUES(confirmation_key), timestamp=VALUES(timestamp), valid_till=VALUES(valid_till)",
			$youser_id,
			$scope,
			$confirmation_key,
			$subject,
			$valid_time,
			$hash
		);

		$confirmation_key = substr($hash, -16).'-'.$confirmation_key;
		return $confirmation_key;
	}

	public static function Delete($youser_id)
	{
		DBManager::Get()->query("DELETE FROM confirmations WHERE youser_id=?", $youser_id);
	}

	public static function GetBySubject($scope, $subject)
	{
		return DBManager::Get()->query("SELECT youser_id, CONCAT(SUBSTR(hash, -16), '-', confirmation_key) AS id FROM confirmations WHERE scope=? AND subject=?", $scope, $subject)->to_array('youser_id', 'id');
	}
}
?>