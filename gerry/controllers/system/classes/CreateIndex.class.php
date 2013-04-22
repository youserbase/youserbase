<?php
class CreateIndex
{
	public function build_index()
	{/*
		$manu = DBManager::Get('devices')->query("SELECT manufacturer_id FROM manufacturer")->to_array('manufacturer_id', 'manufacturer_id');
		$count = 1;
		foreach ($manu as $value)
		{
			DBManager::Get('devices')->query("UPDATE manufacturer SET manufacturer_id_int = ? WHERE manufacturer_id = ?", $count++, $value);
		}*/
		$devices = build_fulltext::get_devices();
		foreach ($devices as $device)
		{
			$data[$device['device_id_int']] = array();
			$tmp = build_fulltext::get_device_indications($device['device_id_int']);
			$indication = '';
			if($tmp !== false && $tmp != null)
			{
				$indication = implode(' ',$tmp);
				unset($tmp);
			}
			$os = '';
			$tmp = build_fulltext::get_operating_systems($device['device_id_int']);
			if($tmp !== false && $tmp != null)
			{
				$os =  implode(' ', $tmp);
				unset($tmp);
			}
			$components = '';
			$tmp = build_fulltext::get_device_components($device['device_id_int']);
			if($tmp !== false && $tmp != null)
			{
				$components = implode(' ', $tmp);
				unset($tmp);
			}
			$tmp = build_fulltext::get_colors($device['device_id_int']);
			$colors = '';
			if($tmp !== false && $tmp != null)
			{
				$colors = implode(' ', $tmp);
				unset($tmp);
			}
			$build_form = '';
			$tmp = build_fulltext::get_buildform($device['device_id_int']);
			if($tmp !== false && $tmp != null)
			{
				$build_form =implode(' ', $tmp);
				unset($tmp);
			}
			DBManager::Get('devices')->query("LOCK TABLES search_index WRITE;");			
			DBManager::Get('devices')->query("INSERT INTO search_index (device_id_int, device_name, manufacturer_name, manufacturer_id, components, colors, build_form, operatingsystem, indication) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE components=VALUES(components);",$device['device_id_int'], $device['device_names_name'], $device['manufacturer_name'], $device['manufacturer_id_int'], $components, $colors, $build_form, $os, $indication);
			DBManager::Get('devices')->query("UNLOCK TABLES;");
		}
		//FrontController::Relocate('Index');
	}
}
?>