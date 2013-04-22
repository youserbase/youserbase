<?php
class syncdevices
{
	public static function get($source_device_id, $target_device_id)
	{
		$source_device_components = DBManager::Get('devices')->query("SELECT table_name, component_id FROM device_components WHERE device_id = ?;", $source_device_id)->to_array('table_name', 'component_id');
		$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $target_device_id)->fetch_item();
		self::update($target_device_id, $source_device_components, $device_id_int);
		if (isset($_REQUEST['relationship']))
		{
			copydevice::create_ancestors($target_device_id, $source_device_id, $_REQUEST['relationship']);
			copydevice::copy_buildin($target_device_id, $device_id_int, $source_device_id);
		}
	}
	
	public static function update($target_device_id, $components, $device_id_int)
	{
		foreach ($components as $table_name => $component_id)
		{
			$component_data = DBManager::Get('devices')->query("SELECT * FROM $table_name WHERE timestamp = (SELECT MAX(timestamp) FROM $table_name WHERE component_id = ?) AND component_id = ?;", $component_id, $component_id)->to_array();
			if($component_data !== false)
			{
				$component_ids = self::update_component($table_name, $component_data);
				if($component_ids !== false)
				{
					self::update_device_components($target_device_id, $device_id_int, $table_name, $component_ids['component_id'], $component_ids['component_id_int']);
				}
			}
		}
	}
	
	private static function update_component($table_name, $component_data)
	{
		$component = new $table_name();
		$component_id = md5(uniqid(time(true)));
		$component_id_int = rand();
		if(is_array($component_data) && !empty($component_data))
		{
			foreach ($component_data as $key => $value)
			{
				if(is_array($value))
				{
					foreach ($value as $attrib => $val)
					{
						if($attrib == 'component_id')
						{
							$val = $component_id;
						}
						else if ($attrib == 'component_id_int')
						{
							$val = $component_id_int;
						}
						else if($attrib == 'youser_id')
						{
							$val = Youser::Id();
						}
						else if($attrib == 'timestamp')
						{
							$val = 'NOW()';
						}
						$component->$attrib = $val;
					}
					$component->save();
				}
				else
				{
					if($key == 'component_id')
					{
						$value = $component_id;
					}
					else if ($key == 'component_id_int')
					{
						$value = $component_id_int;
					}
					else if($key == 'youser_id')
					{
						$value = Youser::Id();
					}
					else if($key == 'timestamp')
					{
						$value = 'NOW()';
					}
					$component->$key = $value;
				}
			}
			$component->save();
			return array('component_id' => $component_id, 'component_id_int' => $component_id_int);
		}
		return false;
	}
	
	private static function update_device_components($device_id, $device_id_int, $table_name, $new_component_id, $new_component_id_int)
	{
		//DBManager::Get('devices')->query("DELETE FROM device_components WHERE device_id = ? AND table_name = ?;", $device_id, $table_name);
		$confirmed = 'no';
		if(Youser::Is('administrator', 'root', 'god'))
		{
			$confirmed = 'yes';
		}
		$device_components = new device_components(md5(uniqid(time(true))),$device_id, $device_id_int, $new_component_id, $new_component_id_int, $table_name, $confirmed, 'NOW()', Youser::Id());
		$device_components->save();
	}
}
?>