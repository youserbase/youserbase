<?php
class Devices_Statistics extends Controller
{
	public function Images()
	{
		$template = $this->get_template(true);
		$template->assign('statistics', DBManager::Get('devices')->query("SELECT device_id, COUNT(device_pictures.device_id) AS images FROM device_names LEFT JOIN device_pictures USING (device_id) GROUP BY device_id ORDER BY images DESC, manufacturer_id")->to_array('device_id', 'images'));
	}
}
?>