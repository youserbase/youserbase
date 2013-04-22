<?php
class deleteDevice
{

	private static $device_id_tables = array('device', 'device_names', 'device_components', 'device_device_types', 'device_pictures', 'device_rating', 'device_rating_unique');

	/**
	 * Deletes Devices
	 * If device_id is empty or false it will delete all deprecated devices (device_ids in device_components but not in device).
	 * Otherwise it will delete All Information regarding the device_id
	 *
	 * @param device_id
	 */
	public static function StartRemoval($device_id = null)
	{
		if($device_id !== null)
		{
			if($tables = investigator::getTablesByID($device_id) !== null)
			{
				if($components = investigator::getComponentsByID($tables) !== null)
				{
					self::delete_components($components);
				}
				self::delete_device_tables($device_id);
				self::delete_predecessors_and_siblings($device_id);
				self::delete_pictures($device_id);
				self::delete_from_statistics($device_id);
				self::delete_from_history($device_id);
				Event::Dispatch('alert', 'Device:Deleted', $device_id);
				Dobber::ReportSuccess('DEVICE DELETE');

				FrontController::Relocate(false);
			}
		}
		foreach(self::$device_id_tables as $table)
		{
			if($table !== 'device')
			{
				cleaner::remove_deprecated_from($table);
			}
			cleaner::remove_deprecated_family();
		}
	}

	private static function delete_components($components)
	{
		if(!empty($components) && is_array($components))
		{
			foreach($components as $table_name => $component_id)
			{
				cleaner::delete_table_by_component_id(str_replace('_type', '', $table_name), $component_id['component_id']);
			}
		}
	}

	private static function delete_device_tables($device_id)
	{
		foreach(self::$device_id_tables as $table_name)
		{
			cleaner::delete_table_by_device_id($table_name, $device_id);
		}
	}

	private static function delete_predecessors_and_siblings($device_id)
	{
		cleaner::delete_predecessors_by_device_id($device_id);
		cleaner::delete_siblings_by_device_id($device_id);
	}

	private static function delete_pictures($device_id)
	{
		$name = substr($device_id, -4);
		$folder = ASSETS_DIR.'device_images/'.$name;
		if(is_dir($folder))
		{
			rmdir($folder);
		}
	}
	
	private static function delete_from_statistics($device_id)
	{
		dbManager::query("DELETE FROM statistics_daily_device_ranks WHERE device_id = ?;", $device_id);
	}
	
	private static function delete_from_history($device_id)
	{
		$new_list = array();
		$old_list = Session::Get('history', 'devices');
		foreach ($old_list as $id)
		{
			if($id != $device_id)
			{
				$new_list[] = $id;
			}
		}
		Session::Set('history', 'devices', $new_list);
	}
}

?>