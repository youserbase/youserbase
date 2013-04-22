<?php
class cleaner
{
	public static function delete_table_by_device_id($table_name, $device_id)
	{
		DBManager::Get('devices')->query("DELETE FROM $table_name WHERE device_id = ?;",
			$device_id
		);
	}
	
	public static function delete_table_by_component_id($table_name, $component_id)
	{
		DBManager::Get('devices')->query("DELETE FROM $table_name WHERE component_id = ?;",
			$component_id
		);
	}
	
	public static function delete_predecessors_by_device_id($device_id)
	{
		DBManager::Get('devices')->query("DELETE FROM predecessors WHERE parent_id = ? OR child_id = ?;",
			$device_id,
			$device_id
		);
	}
	
	public static function delete_siblings_by_device_id($device_id)
	{
		DBManager::Get('devices')->query("DELETE FROM siblings WHERE brother_id = ? OR sister_id = ?;",
			$device_id,
			$device_id
		);
	}
	
	public static function remove_deprecated_from($table_name)
	{
		if($table_name == 'device_similarity' || $table_name == 'component_similarity')
		{
			self::remove_deprecated_from_similarity();
		}
		else
			DBManager::Get('devices')->query("DELETE FROM $table_name WHERE device_id NOT IN (SELECT device_id FROM device);",
			$table_name
			);
	}
	
	public static function remove_deprecated_from_similarity()
	{
		DBManager::Get('devices')->query("DELETE FROM device_similarity Where device_id OR compared_id NOT IN (SELECT device_id FROM device);");
		DBManager::Get('devices')->query("DELETE FROM component_similarity Where device_id OR compared_id NOT IN (SELECT device_id FROM device);");
	}
	
	public static function remove_deprecated_family()
	{
		DBManager::Get('devices')->query("DELETE FROM siblings Where brother_id OR sister_id NOT IN (SELECT device_id FROM device);");
		DBManager::Get('devices')->query("DELETE FROM predecessors Where parent_id OR child_id NOT IN (SELECT device_id FROM device);");
	}
}
?>