<?php
class build_fulltext
{
	public function get_devices()
	{
		$devices = DBManager::Get('devices')->query("SELECT device.device_id_int, device_names_name, manufacturer_name, manufacturer_id_int, device_id FROM device LEFT JOIN device_names USING(device_id) LEFT JOIN manufacturer USING(manufacturer_id) WHERE (device_names_name != '')ORDER BY device.device_id_int;")->to_array();
		return $devices;
	}
	
	public function get_device_translations($device_name)
	{
		$names = DBManager::Get()->query("SELECT phrase FROM phrases WHERE phrase_id LIKE ?;", $device_name)->to_array('phrase', 'phrase');
		return $names;
	}
	
	public function get_device_components($device_id)
	{
		$components = DBManager::Get('devices')->query("SELECT component_name FROM build_in WHERE build_in_status NOT LIKE 'no' AND device_id = ?;", $device_id)->to_array('component_name', 'component_name');
		return $components;
		
	}
	
	public function get_device_indications($device_id)
	{
		$indications = false;
		$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name LIKE 'mobilephone' AND device_id = ?;", $device_id)->fetch_item();
		if($component_id != null)
		{
			$indications = DBManager::Get('devices')->query("SELECT indication_type_name FROM indication_type LEFT JOIN mobilephone USING(indication_type_id) WHERE component_id = ? AND mobilephone.timestamp = (SELECT MAX(timestamp) FROM mobilephone WHERE component_id = ?);", $component_id, $component_id)->to_array('indication_type_name', 'indication_type_name');
		}
		return $indications;
	}
	
	public function get_operating_systems($device_id)
	{
		$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name LIKE 'operatingsystem' AND device_id = ?;", $device_id)->fetch_item();
		if($component_id !== null)
		{
			$os = DBManager::Get('devices')->query("SELECT operatingsystem_type_name FROM operatingsystem_type LEFT JOIN operatingsystem USING(operatingsystem_type_id) WHERE component_id = ? ORDER BY operatingsystem.timestamp DESC LIMIT 0,1;", $component_id)->to_array('operatingsystem_type_name', 'operatingsystem_type_name');
			return $os;
		}
		return false;
	}
	
	public function get_colors($device_id)
	{
		$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name LIKE 'color' AND device_id = ?;", $device_id)->fetch_item();
		if($component_id !== null)
		{
			$color = DBManager::Get('devices')->query("SELECT color_type_name FROM color_type LEFT JOIN color USING(color_type_id) WHERE component_id = ? AND color.timestamp = (SELECT MAX(timestamp) FROM color WHERE component_id = ?)", $component_id, $component_id)->to_array('color_type_name', 'color_type_name');
			return $color;
		}
		return false;
	}
	
	public function get_buildform($device_id)
	{
		$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name LIKE 'body' AND device_id = ?;", $device_id)->fetch_item();
		if($component_id !== null)
		{
			$color = DBManager::Get('devices')->query("SELECT body_type_name FROM body_type LEFT JOIN body USING(body_type_id) WHERE component_id = ? ORDER BY body.timestamp DESC LIMIT 0,1", $component_id)->to_array('body_type_name', 'body_type_name');
			return $color;
		}
		return false;
	}
	
	public function get_releaseyear($device_id)
	{
		$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name LIKE 'market_information' AND device_id = ?;", $device_id)->fetch_item();
		if($component_id !== null)
		{
			$release_year = DBManager::Get('devices')->query("SELECT release_year FROM market_information WHERE component_id = ? ORDER BY timestamp DESC LIMIT 0,1", $component_id)->fetch_item();
			return $release_year;
		}
		return false;
	}
	
	
	public function get_inputmethod($device_id)
	{
		$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name LIKE 'input_method' AND device_id = ?;", $device_id)->fetch_item();
		if($component_id !== null)
		{
			$input_method = DBManager::Get('devices')->query("SELECT input_method_type_name FROM input_method_type LEFT JOIN input_method USING(input_method_type_id) WHERE component_id = ? AND input_method.timestamp = (SELECT MAX(timestamp) FROM input_method WHERE component_id LIKE ?);", $component_id, $component_id)->to_array('input_method_type_name', 'input_method_type_name');
			return $input_method;
		}
		return false;
	}
}
?>