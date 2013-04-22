<?php
class Hook_Tags extends Hook
{
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

		$result = DBManager::Get()->query("SELECT tag FROM youser_device_tags WHERE tag LIKE '".implode("%' OR tag LIKE '", $needle)."%' GROUP BY tag")->to_array(null, 'tag');

		$search_result = array();
		foreach ($result as $tag)
		{
			array_push($search_result, array(
				'url' => FrontController::GetLink('Devices', 'Tags', 'Tag', array('tag'=>$tag)),
				'name'=> $tag,
			));
		}

		return array(
			'tags' => $search_result
		);
	}

	public static function GarbageCollector()
	{
		DBManager::Get()->query("DELETE FROM youser_device_tags WHERE device_id NOT IN (SELECT device_id FROM temp_consultant)");
	}
}
?>