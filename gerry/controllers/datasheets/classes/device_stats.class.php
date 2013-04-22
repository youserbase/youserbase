<?php
class device_stats
{
	public static function get_counts($device_id, $device_id_int = null)
	{
		$requests = DBManager::Get('devices')->query("SELECT COUNT(youser_id) as absolute, COUNT(DISTINCT(youser_id)) as relative FROM device_statistics WHERE device_id = ?", $device_id)->to_array('relative', 'absolute');
		if($requests == null)
		{
			$requests = 0;
		}
		return $requests;
	}

	public static function update_stats($device_id, $device_id_int =  null, $tab = '')
	{
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
			$agent = 'youser';
		}
		else
		{
			$agent = implode(' , ', $_SERVER);
			if (strpos($agent, 'Google') !== false || strpos($agent, 'googlebot') !== false || strpos($agent, 'Yandex') !== false || strpos($agent, 'Yahoo') !== false || strpos($agent, 'spider') !== false || strpos($agent, 'bot') !== false || strpos($agent, 'Amazon') !== false || strpos($agent, 'alexa') !== false || strpos($agent, 'crawler') !== false) {
				return false;
			}
			$youser_id = $_SERVER['REMOTE_ADDR'];

		}
		if($device_id_int == null)
		{
			$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
		}
		if($device_id_int == null){
			$device_id_int = 0;
		}
		DBManager::Get('devices')->query("INSERT DELAYED INTO device_statistics (device_id, device_id_int, youser_id, tab, language) VALUES(?,?,?,?,?)", $device_id, $device_id_int, $youser_id,$tab, BabelFish::GetLanguage());

	}

	public static function get_stats($limit, $skip = 0)
	{
		$time = DBManager::Get('devices')->query("SELECT DISTINCT(timestamp) FROM device_statistics_tmp")->fetch_item();
		$devices = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id FROM device_statistics_tmp ORDER BY impact DESC")->to_array(null, 'device_id');
		if($devices == null)
		{
			self::build_stats();
			$devices = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id FROM device_statistics_tmp ORDER BY impact DESC")->to_array(null, 'device_id');
		}
		return $devices;
	}

	public static function build_stats()
	{
		$devices = DBManager::Get('devices')->query("SELECT DISTINCT(device_id) FROM device_statistics")->to_array(null, 'device_id');
		$months = array(1, 2, 3, 4);
		foreach ($devices as $device_id)
		{
			foreach ($months as $month)
			{
				$from = date('Y').'-'.(date('m')-$month).'-00 00:00:00';
				$till = date('Y').'-'.(date('m')-$month+1).'-31 00:00:00';
				$rating = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT(youser_id)) as impact FROM device_statistics WHERE timestamp > ? AND timestamp < ? AND device_id = ? GROUP BY device_id ORDER BY COUNT(DISTINCT(youser_id)) DESC", $from, $till, $device_id)->fetch_item();
				$impact[$month] = $rating/$month;
			}
			$device_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
			$ids[$device_id] = array_sum($impact);
			$stats = new device_statistics_tmp($device_id, $device_int, array_sum($impact));
			$stats->save();
		}
		foreach (BabelFish::GetLanguages() as $language)
		{
			foreach ($devices as $device_id)
			{
				foreach ($months as $month)
				{
					$from = date('Y').'-'.(date('m')-$month).'-00 00:00:00';
					$till = date('Y').'-'.(date('m')-$month+1).'-31 00:00:00';
					$rating = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT(youser_id)) as impact FROM device_statistics WHERE timestamp > ? AND timestamp < ? AND device_id = ? AND language = ? GROUP BY device_id ORDER BY COUNT(DISTINCT(youser_id)) DESC", $from, $till, $device_id, $language)->fetch_item();
					$impact[$month] = $rating/$month;
				}
				$device_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id, $language)->fetch_item();
				$ids[$device_id] = array_sum($impact);
				$stats = new device_statistics_tmp();
				$stats->device_id = $device_id;
				$stats->device_id_int = $device_int;
				$stats->impact = array_sum($impact);
				$stats->langugae = $language;
				$stats->save();
			}
		}
	}
}
?>