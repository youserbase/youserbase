<?php 
class computesimilarity
{
	public static function compute_similarity()
	{
		/*
		self::computeComponentSimilarity();
		self::computeDeviceSimilarity();
		*/
	}
	
	private static function computeComponentSimilarity()
	{
		$devices = self::getAllDevices();
		$device_ids = array_keys($devices);
		$devices = self::getDeviceData($devices);
		foreach($device_ids as $device_id)
		{
			if(isset($devices[$device_id]))
				{
				$rootdevice = $devices[$device_id];
				foreach($devices as $id => $device_data)
				{
					if($id != $device_id)
					{
						foreach($device_data as $table => $table_values)
						{
							if(isset($rootdevice[$table]))
							{
								$root_count = count($rootdevice[$table]);
								$comp_count = count($table_values);
								$diff = array_diff($table_values, $rootdevice[$table]);
								$sim = ($root_count - count($diff))/$root_count;
								if($sim < 0)
									$sim = ($comp_count - count($diff))/$comp_count;
							}
							else $sim = 0;
							$component = new component_similarity(md5(uniqid(true)));
							$component->device_id = $device_id;
							$component->compared_id = $id;
							$component->table_name = $table;
							$component->similarity = $sim;
							$component->save();
						}
					}
				}	
			}
		}
	}
	
	private static function computeDeviceSimilarity()
	{
		$db = DBManager::Get('devices');
		$devices = self::getAllDevices();
		$device_ids = array_keys($devices);
		foreach($device_ids as $device_id)
		{
			$result = $db->query("SELECT compared_id, AVG(similarity) AS avg FROM component_similarity WHERE device_id = ? GROUP BY compared_id;", $device_id);
			if(!$result->is_empty())
			{
				while($data = $result->fetch_Array())
				{
					$dev = new device_similarity(md5(uniqid(true)));
					$dev->device_id = $device_id;
					$dev->compared_id = $data['compared_id'];
					$dev->similarity = $data['avg'];
					$dev->save();
				}
			}
		}
	}
	
	private static function getAllDevices()
	{
		$db = DBManager::Get('devices');
		$result = $db->query("SELECT device_id, table_name, component_id FROM device_components;");
		if($result->is_empty())
			return false;
			
		while ($data = $result->fetch_Array())
		{
			$devices[$data['device_id']][$data['table_name']] = $data['component_id'];
		}
		$result->release();
		return $devices;
	}
	
	private static function getDeviceData($devices)
	{
		$db = DBManager::Get('devices');
		foreach($devices as $device_id => $table_data)
		{
			foreach($table_data as $table_name => $component_id)
			{
				$table_name = str_replace('_type', '', $table_name);
				$result = $db->query("SELECT * FROM $table_name WHERE component_id = ? AND timestamp = (SELECT MAX(timestamp) FROM $table_name WHERE component_id =?);", $component_id, $component_id);
				if(!$result->is_empty())
				{
					while ($data = $result->fetch_Array())
					{
						foreach($data as $key => $value)
						{
							if($key != 'timestamp' && $key != 'youser_id' && $key != 'component_id' && $key != $table_name.'_id' && isset($value) && !empty($value) && $value != 0)
								$device[$device_id][$table_name][] = $value;
								$device[$device_id][$table_name]['component_id'] = $component_id;
						}
					}
				}
			}
		}
		return $device;
	}
	
	private static function start_component_similarity()
	{
		$device_ids = investigator::getAllDeviceIds();
		foreach ($device_ids as $device_id)
		{
			$tables = investigator::getTablesByID($device_id);
			$source_components = investigator::getComponentsByID($tables);
			foreach ($device_ids as $compare_id)
			{
				if($compare_id !== $device_id)
				{
					$compare_tables = investigator::getTablesByID($compare_id);
					$compare_components = investigator::getComponentsByID($compare_tables);
				}
			}
		}
	}
	
	private static function compute_component_similarity($source_components, $compare_components)
	{
	}
}
?>