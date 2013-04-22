<?php
class DeviceHook extends Hook
{
	private static $alias_cache = array();

	public static $hooks = array(
		'Global:Search'=>'Search',
		'Global:Search_AJAX'=>'Search_AJAX',
		'Garbage:Collect'=>'GarbageCollector',
	);

	public static function Search($needle, $translated=false)
	{
		$phrases = BabelFish::Search($needle);
		if (empty($phrases))
		{
			return array();
		}

		$result = DBManager::Get('devices')->query("SELECT device_id, device_names_name, manufacturer_name FROM device_names LEFT JOIN manufacturer USING(manufacturer_id) WHERE device_names_name IN (?) OR manufacturer_name IN (?)", $phrases, $phrases);

		$search_results = array();
		while ($row = $result->fetch_array())
		{
			if ($translated)
			{
				$row['url'] = DeviceHelper::GetLink($row['device_id'], $row['manufacturer_name'], $row['device_names_name']);
				$row['device_name'] = BabelFish::Get($row['device_names_name']);
				$row['manufacturer_name'] = BabelFish::Get($row['manufacturer_name']);
			}

			$search_result = new SearchResult_Device($row);
			$search_result->set_needle($needle);
			array_push($search_results, $search_result);
		}
		$result->release();

		return $search_results;
	}

	public static function Search_AJAX($needle)
	{
		$needle = array_filter(explode(' ', $needle));
		$phrases = BabelFish::Search($needle);

		$result = empty($phrases)
			? DBManager::Get('devices')->query("SELECT device_names.device_id, device_names_name, device_names.manufacturer_id, manufacturer_name, GROUP_CONCAT(device_type_name) AS device_type FROM device_names LEFT JOIN manufacturer USING(manufacturer_id) LEFT JOIN device_device_types ON (device_names.device_id=device_device_types.device_id) WHERE device_names.device_id IN (?) GROUP BY device_names.device_id",
				$needle
			)
			: DBManager::Get('devices')->query("SELECT device_names.device_id, device_names_name, device_names.manufacturer_id, manufacturer_name, GROUP_CONCAT(device_type_name) AS device_type FROM device_names LEFT JOIN manufacturer USING(manufacturer_id) LEFT JOIN device_device_types ON (device_names.device_id=device_device_types.device_id) WHERE device_names_name IN (?) OR manufacturer_name IN (?) OR device_names.device_id IN (?) GROUP BY device_names.device_id",
				$phrases,
				$phrases,
				$needle
			);

		$search_results = array(
			'models'=>array(),
			'manufacturers'=>array(),
			'types'=>array()
		);
		while ($row = $result->fetch_array())
		{
			$name = BabelFish::Get($row['manufacturer_name']).' '.BabelFish::Get($row['device_names_name']);

			foreach ($needle as $atom)
			{
				if (stripos($name, $atom)===false)
				{
//					continue 2;
				}
			}

			array_push($search_results['models'], array(
				'url'=>DeviceHelper::GetLink($row['device_id'], $row['manufacturer_name'], $row['device_names_name']),
				'name'=>$name,
				'type'=>explode(',', $row['device_type'])
			));
			if (!isset($search_results['manufacturers'][$row['manufacturer_id']]))
			{
				$search_results['manufacturers'][$row['manufacturer_id']] = array(
					'url'=>FrontController::GetLink('Datasheets', 'Catalogue', 'Manufacturer', array('manufacturer_id'=>$row['manufacturer_id'], 'catalogue'=>'device_types_by_manufacturer')),
					'name'=>BabelFish::Get($row['manufacturer_name'])
				);
			}
			foreach (array_filter(explode(',', $row['device_type'])) as $device_type)
			{
				if (!isset($search_results['types'][$device_type]))
				{
					$search_results['types'][$device_type] = array(
						'url'=>FrontController::GetLink('Datasheets', 'Catalogue', 'Device_Type', array('device_type'=>$device_type, 'catalogue'=>'manufacturer_by_device_types')),
						'name'=>BabelFish::Get($device_type)
					);
				}
			}
		}
		$result->release();

		usort($search_results['manufacturers'], create_function('$a,$b', 'return strcmp($a["name"], $b["name"]);'));
		usort($search_results['types'], create_function('$a,$b', 'return strcmp($a["name"], $b["name"]);'));

		return $search_results;
	}

	public static function GarbageCollector()
	{
		DBManager::Get()->query("DELETE FROM youser_devices WHERE device_id NOT IN (SELECT device_id FROM temp_consultant)");
	}
}
?>