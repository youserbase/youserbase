<?php
	define('MAX', 1000);

	require dirname(__FILE__).'/../classes/vendor/simplepie.inc';
	require dirname(__FILE__).'/../classes/ClassLoader.class.php';
	require dirname(__FILE__).'/../includes/classloader.inc.php';
	require dirname(__FILE__).'/../includes/config.inc.php';
	require dirname(__FILE__).'/../includes/functions.inc.php';

	$statistics = array();

	$result = DBManager::Get()->limit(MAX)->query("SELECT via_ajax, session_id, youser_id, get_parameters, post_parameters, location, UNIX_TIMESTAMP(timestamp) AS timestamp, user_agent, language FROM log_hits ORDER BY timestamp ASC");
	foreach ($result->to_array() as $row)
	{
		$timestamp = strtotime('today 0:00:00', $row['timestamp']);
		if (!isset($statistics[$timestamp]))
		{
			$statistics[$timestamp] = array(
				'devices'=>array(),
				'languages'=>array(),
				'manufacturers'=>array(),
				'page_impressions'=>0,
				'sessions'=>array(),
				'user_agents'=>array(),
				'via_ajax'=>0,
				'yousers'=>array(),
			);
		}

		$statistics[$timestamp]['page_impressions'] += 1;
		$statistics[$timestamp]['via_ajax'] += (($row['via_ajax']=='yes')?1:0);

		$get = (array)unserialize($row['get_parameters']);
		$post = (array)unserialize($row['post_parameters']);

		$parameters = array_merge($get, $post);

		if (!empty($parameters['device_id']))
		{
			$device_id = $parameters['device_id'];
			if (!isset($statistics[$timestamp]['devices'][$device_id]))
			{
				$statistics[$timestamp]['devices'][$device_id] = 0;
			}
			$statistics[$timestamp]['devices'][$device_id] += 1;

			$manufacturer_id = DBManager::Get('devices')->query("SELECT manufacturer_id FROM device_names WHERE device_id=?", $parameters['device_id'])->fetch_item();
			if ($manufacturer_id !== null)
			{
				if (!isset($statistics[$timestamp]['manufacturers'][$manufacturer_id]))
				{
					$statistics[$timestamp]['manufacturers'][$manufacturer_id] = 0;
				}
				$statistics[$timestamp]['manufacturers'][$manufacturer_id] += 1;
			}
		}

		if (!isset($statistics[$timestamp]['user_agent'][$row['user_agent']]))
		{
			$statistics[$timestamp]['user_agents'][$row['user_agent']] = 0;
		}
		$statistics[$timestamp]['user_agents'][$row['user_agent']] += 1;

		if (!empty($row['language']))
		{
			if (!isset($statistics[$timestamp]['languages'][$row['language']]))
			{
				$statistics[$timestamp]['languages'][$row['language']] = 0;
			}
			$statistics[$timestamp]['languages'][$row['language']] += 1;
		}

		array_push($statistics[$timestamp]['sessions'], $row['session_id']);
		if (!empty($row['youser_id']))
		{
			array_push($statistics[$timestamp]['yousers'], $row['youser_id']);
		}

		DBManager::Get()->query("INSERT INTO statistics_daily_sites (daystamp, site, page_impressions, yousers, via_ajax) VALUES (FROM_UNIXTIME(?), ?, 1, ?, ?) ON DUPLICATE KEY UPDATE page_impressions=page_impressions+VALUES(page_impressions), yousers=yousers+VALUES(yousers), via_ajax=via_ajax+VALUES(via_ajax)",
			$timestamp,
			$row['location'],
			empty($row['youser_id']) ? 0 : 1,
			($row['via_ajax']=='yes') ? 1 : 0
		);
	}

	if (empty($statistics))
	{
		die;
	}

	foreach ($statistics as $daystamp=>$stats)
	{
		$statistics[$daystamp]['yousers'] = array_unique($statistics[$daystamp]['yousers']);

		foreach ($stats['devices'] as $device_id=>$count)
		{
			DBManager::Query("INSERT INTO statistics_daily_devices (daystamp, device_id, count) VALUES (FROM_UNIXTIME(?), ?, ?) ON DUPLICATE KEY UPDATE count=count+VALUES(count)",
				$daystamp,
				$device_id,
				$count
			);
		}

		foreach ($stats['manufacturers'] as $manufacturer_id => $count)
		{
			DBManager::Query("INSERT INTO statistics_daily_manufacturers (daystamp, manufacturer_id, count) VALUES (FROM_UNIXTIME(?), ?, ?) ON DUPLICATE KEY UPDATE count=count+VALUES(count)",
				$daystamp,
				$manufacturer_id,
				$count
			);
		}

		foreach ($stats['user_agents'] as $user_agent=>$count)
		{
			DBManager::Query("INSERT INTO statistics_daily_browser (daystamp, user_agent, count) VALUES (FROM_UNIXTIME(?), ?, ?) ON DUPLICATE KEY UPDATE count=count+VALUES(count)",
				$daystamp,
				$user_agent,
				$count
			);
		}

		foreach ($stats['languages'] as $language=>$count)
		{
			DBManager::Query("INSERT INTO statistics_daily_languages (daystamp, language, count) VALUES (FROM_UNIXTIME(?), ?, ?) ON DUPLICATE KEY UPDATE count=count+VALUES(count)",
				$daystamp,
				$language,
				$count
			);
		}

		foreach (array_unique($stats['sessions']) as $session_id)
		{
			DBManager::Query("INSERT IGNORE INTO statistics_temp_daily_sessions (daystamp, session_id) VALUES (FROM_UNIXTIME(?), ?)",
				$daystamp,
				$session_id
			);
		}

		foreach (array_unique($stats['yousers']) as $youser_id)
		{
			DBManager::Query("INSERT IGNORE INTO statistics_temp_daily_yousers (daystamp, youser_id) VALUES (FROM_UNIXTIME(?), ?)",
				$daystamp,
				$youser_id
			);
		}

		$yousers = DBManager::Query("SELECT COUNT(*) FROM statistics_temp_daily_yousers WHERE daystamp=FROM_UNIXTIME(?)", $daystamp)->fetch_item();
		$sessions = DBManager::Query("SELECT COUNT(*) FROM statistics_temp_daily_sessions WHERE daystamp=FROM_UNIXTIME(?)", $daystamp)->fetch_item();

		DBManager::Query("INSERT INTO statistics_daily (daystamp, page_impressions, via_ajax, visitors, yousers) VALUES (FROM_UNIXTIME(?), ?, ?, ?, ?) ON DUPLICATE KEY UPDATE page_impressions=page_impressions+VALUES(page_impressions), via_ajax=via_ajax+VALUES(via_ajax), visitors=VALUES(visitors), yousers=VALUES(yousers)",
			$daystamp,
			$stats['page_impressions'],
			$stats['via_ajax'],
			$sessions,
			$yousers
		);
	}

	DBManager::Get()->limit(MAX)->query("DELETE QUICK FROM log_hits ORDER BY timestamp ASC");
	DBManager::Query("OPTIMIZE TABLE log_hits");

	DBManager::Query("DELETE FROM statistics_temp_daily_yousers WHERE daystamp<FROM_UNIXTIME(?)", max(array_keys($statistics)));
	DBManager::Query("DELETE FROM statistics_temp_daily_sessions WHERE daystamp<FROM_UNIXTIME(?)", max(array_keys($statistics)));

	// Garbage Collect

	$all_device_ids = DBManager::Query("SELECT DISTINCT device_id FROM temp_consultant")->to_array(null, 'device_id');

	DBManager::Query("DELETE FROM statistics_daily_devices WHERE device_id NOT IN (?)", $device_ids);
	DBManager::Query("DELETE FROM statistics_daily_device_ranks WHERE device_id NOT IN (?)", $device_ids);

	$all_manufacturer_ids = DBManager::Query("SELECT DISTINCT manufacturer_id FROM temp_consultant")->to_array(null, 'manufacturer_id');
	DBManager::Query("DELETE FROM statistics_daily_manufacturers WHERE manufacturer_id NOT IN (?)", $manufacturer_ids);
	DBManager::Query("DELETE FROM statistics_daily_manufacturer_ranks WHERE manufacturer_id NOT IN (?)", $manufacturer_ids);

	// Create ranks and other useful stuff

	$yesterday = strtotime('yesterday 0:0:0');
	$today = strtotime('today 0:0:0');

	if (max(array_keys($statistics))==$today and !DBManager::Query("SELECT 1 FROM statistics_daily_device_ranks WHERE daystamp=FROM_UNIXTIME(?)", $yesterday)->fetch_item())
	{
		$devices = DBManager::Query("SELECT device_id, count FROM statistics_daily_devices WHERE daystamp=FROM_UNIXTIME(?) ORDER BY count DESC",
			$yesterday
		)->to_array('device_id', 'count');

		$rank = 0;
		$old_count = -1;

		foreach ($devices as $device_id => $count)
		{
			if ($old_count != $count)
			{
				$old_count = $count;
				$rank += 1;
			}

			DBManager::Query("INSERT INTO statistics_daily_device_ranks (daystamp, device_id, rank) VALUES (FROM_UNIXTIME(?),?,?)",
				$yesterday,
				$device_id,
				$rank
			);
		}

		$rank = $rank + 1;

		foreach (array_diff($all_device_ids, array_keys($devices)) as $device_id)
		{
			DBManager::Query("INSERT INTO statistics_daily_device_ranks (daystamp, device_id, rank) VALUES (FROM_UNIXTIME(?), ?, ?)",
				$yesterday,
				$device_id,
				$rank
			);
		}

		$manufacturers = DBManager::Query("SELECT manufacturer_id, count FROM statistics_daily_manufacturers WHERE daystamp=FROM_UNIXTIME(?) ORDER BY count DESC",
			$yesterday
		)->to_array('manufacturer_id', 'count');

		$rank = 0;
		$old_count = -1;

		foreach ($manufacturers as $manufacturer_id => $count)
		{
			if ($old_count != $count)
			{
				$old_count = $count;
				$rank += 1;
			}

			DBManager::Query("INSERT INTO statistics_daily_manufacturer_ranks (daystamp, manufacturer_id, rank) VALUES (FROM_UNIXTIME(?),?,?)",
				$yesterday,
				$manufacturer_id,
				$rank
			);
		}

		$rank = $rank + 1;

		foreach (array_diff($all_manufacturer_ids, array_keys($manufacturers)) as $manufacturer_id)
		{
			DBManager::Query("INSERT INTO statistics_daily_manufacturer_ranks (daystamp, manufacturer_id, rank) VALUES (FROM_UNIXTIME(?), ?, ?)",
				$yesterday,
				$manufacturer_id,
				$rank
			);
		}
	}
?>