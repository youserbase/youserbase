<?php
class Plugin_Consultant extends Plugin
{
	public static $cronjob = 600;
	public function cronjob()
	{
		$last_timestamp = DBManager::Get()->query("SELECT UNIX_TIMESTAMP(MAX(last_update)) FROM temp_consultant")->fetch_item();

		$device_ids = DBManager::Get('devices')->query("SELECT device_id FROM device")->to_array(null, 'device_id');
		$manufacturers = DBManager::Get('devices')->query("SELECT device_id, manufacturer_id FROM device_names")->to_array('device_id', 'manufacturer_id');
		$manufacturer_names = DBManager::Get('devices')->query("SELECT manufacturer_id, manufacturer_name FROM manufacturer")->to_array('manufacturer_id', 'manufacturer_name');
		$shapes = DBManager::Get('devices')->query("SELECT device_id, MAX(body_type_name) FROM device_components c JOIN body b USING (component_id) JOIN body_type bt ON b.body_type_id=bt.body_type_id WHERE table_name='body' GROUP BY device_id")->to_array('device_id', ':2');
		$input_methods = DBManager::Get('devices')->query("SELECT device_id, MAX(input_method_type_name) FROM device_components c JOIN input_method i USING (component_id) JOIN input_method_type it ON i.input_method_type_id=it.input_method_type_id WHERE table_name='input_method' GROUP BY device_id")->to_array('device_id', ':2');
		$pictures = DBManager::Get('devices')->query("SELECT device_id, COUNT(*) FROM device_pictures GROUP BY device_id")->to_array('device_id', ':2');
		$ratings = DBManager::Get()->query("SELECT device_id, SUM(count) FROM statistics_daily_devices WHERE daystamp>TIMESTAMPADD(WEEK, -1, NOW()) GROUP BY device_id")->to_array('device_id', ':2');

		foreach ($device_ids as $device_id)
		{
			if (!isset($manufacturers[$device_id]))
			{
				continue;
			}
			DBManager::Get()->query("INSERT INTO temp_consultant (device_id, manufacturer_id, manufacturer_name, shape, input_method, pictures, rating, last_update) VALUES (?, ?, ?, ?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE manufacturer_id=VALUES(manufacturer_id), manufacturer_name=VALUES(manufacturer_name), shape=VALUES(shape), input_method=VALUES(input_method), pictures=VALUES(pictures), rating=VALUES(rating), last_update=VALUES(last_update)",
				$device_id,
				$manufacturers[$device_id],
				isset($manufacturer_names[$manufacturers[$device_id]]) ? $manufacturer_names[$manufacturers[$device_id]] : null,
				isset($shapes[$device_id]) ? $shapes[$device_id] : null,
				isset($input_methods[$device_id]) ? $input_methods[$device_id] : null,
				isset($pictures[$device_id]) ? $pictures[$device_id] : 0,
				isset($ratings[$device_id]) ? $ratings[$device_id] : 0
			);
		}
		DBManager::Get()->query("DELETE FROM temp_consultant WHERE device_id NOT IN (?)", $device_ids);
	}

	public function fill_template(&$template)
	{
		return false;
	}
}
?>