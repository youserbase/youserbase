<?php
class initialImport extends Controller 
{
	public function Index()
	{
		$template = $this->get_template(true);
	}
	
	public function update_body()
	{
		$template = $this->get_template(true);
		$devices = DBManager::Get('devices')->query("SELECT device_id, device_names_name, manufacturer_name, m.manufacturer_id FROM device_names as dn LEFT JOIN manufacturer as m ON dn.manufacturer_id = m.manufacturer_id")->to_array();
		foreach ($devices as $device_data)
		{
			$device_id = $device_data['device_id'];
			$manufacturer_id = $device_data['manufacturer_id'];
			$manufacturer_name_phrase = $device_data['manufacturer_name'];
			if(($manufacturer_name = BabelFish::Get($manufacturer_name_phrase)) != $manufacturer_name_phrase);
			{
				$device_name_phrase = $device_data['device_names_name'];
				if(($device_name = BabelFish::Get($device_name_phrase)) != $device_name_phrase)
				{
					$data = ContentGrabber::GetDataForModel($manufacturer_name, $device_name);
					print_r($data).die();
					/*if(isset($data['inside-handy.de']['hardware']['dimensions']))
					{
						$data = $data['inside-handy.de']['hardware']['dimensions'];
						if(is_array($data))
						{
							$length = reset($data);
							$length = floatval(str_replace(',', '.', $length));
							$width = next($data);
							$width = floatval(str_replace(',', '.', $width));
							$thickness = next($data);
							$thickness = str_replace('mm', '', $thickness);
							$thickness = floatval(str_replace(',', '.', $thickness));
							
							$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name = 'body' AND device_id = ? ORDER BY timestamp LIMIT 0,1;", $device_id)->fetch_item();
					DBManager::Get('devices')->query("UPDATE body SET body_width = ?, body_length = ?, body_thickness = ? WHERE component_id = ?;", $width, $length, $thickness, $component_id);
						}
					}*/
				}
			}
		}
	}
	
	public function set_component_notbuildin()
	{
		if(!isset($_GET['component']))
		{
			FrontController::Relocate('Index');
		}
		$component = $_GET['component'];
		$devices = DBManager::Get('devices')->query("SELECT DISTINCT(device_id) FROM device;")->to_array();
		foreach ($devices as $device_id)
		{
			$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
			$build_in = new build_in('', $device_id, $device_id_int, $component, 'no', Youser::Id(), 'NOW()');
			$build_in->save();
		}
		FrontController::Relocate('Index', array('status' => 'changed_component'));
	}
}
?>