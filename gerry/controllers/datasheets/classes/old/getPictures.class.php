<?php
class getPictures
{
	public static function getPicturePaths($device_id)
	{
		$db = DBManager::Get('devices');
		return array();
		$result = $db->query("SELECT device_pictures_path FROM device_pictures WHERE device_id = ?;", $device_id);
		$device_pictures = false;
		if($result->is_empty())
		{
			return false;
		}
		while($data = $result->fetchArray())
		{
			$device_pictures[] = $data['device_pictures_path'];
		}
		$result->release();
		return $device_pictures;
	}
}
?>
