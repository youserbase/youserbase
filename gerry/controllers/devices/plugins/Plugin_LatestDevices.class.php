<?php
class Plugin_LatestDevices extends Plugin
{
	public static $options = array(
		'display_limit:range:1,16',
	);

	public function skip_device()
	{
		Session::Set('skip_latest_device', $_GET['skip']);
	}

	public function fill_template(&$template)
	{
		$limit = $this->get_config('display_limit');

		$skip = 0;
		if (Session::Get('skip_latest_device'))
		{
			$skip = Session::Get('skip_latest_device');
		}

		$devices = DBManager::Get('devices')->limit($limit)->skip($skip)->query("SELECT device.device_id FROM device LEFT JOIN device_names USING(device_id) WHERE confirmed='yes' AND device_names.device_id IS NOT NULL ORDER BY device_names.timestamp DESC")->to_array(null, 'device_id');

		if (empty($devices))
		{
			return false;
		}

		$template->assign('devices', $devices);
		$template->assign('limit', $limit);
		$template->assign('skip', $skip);
		$template->assign('total', DeviceHelper::GetDeviceCount());
		return true;
	}
	
	public function fill_template_mobilephone(&$template)
	{
		$limit = $this->get_config('display_limit');

		$skip = 0;
		if (Session::Get('skip_latest_device'))
		{
			$skip = Session::Get('skip_latest_device');
		}

		$devices = DBManager::Get('devices')->limit($limit)->skip($skip)->query("SELECT device.device_id FROM device LEFT JOIN device_names USING(device_id) LEFT JOIN device_device_types USING(device_id) WHERE confirmed='yes' AND device_names.device_id IS NOT NULL AND device_type_name LIKE 'MOBILEPHONE' ORDER BY device_names.timestamp DESC")->to_array(null, 'device_id');

		if (empty($devices))
		{
			return false;
		}

		$template->assign('devices', $devices);
		$template->assign('limit', $limit);
		$template->assign('skip', $skip);
		$template->assign('total', DeviceHelper::GetDeviceCount());
		return true;
	}
	
	public function fill_template_computer(&$template)
	{
		$limit = $this->get_config('display_limit');

		$skip = 0;
		if (Session::Get('skip_latest_device'))
		{
			$skip = Session::Get('skip_latest_device');
		}

		$devices = DBManager::Get('devices')->limit($limit)->skip($skip)->query("SELECT device.device_id FROM device LEFT JOIN device_names USING(device_id) LEFT JOIN device_device_types USING(device_id) WHERE confirmed='yes' AND device_names.device_id IS NOT NULL AND device_type_name LIKE 'TABLET' OR device_type_name LIKE 'NETBOOK' ORDER BY device_names.timestamp DESC")->to_array(null, 'device_id');

		if (empty($devices))
		{
			return false;
		}

		$template->assign('devices', $devices);
		$template->assign('limit', $limit);
		$template->assign('skip', $skip);
		$template->assign('total', DeviceHelper::GetDeviceCount());
		return true;
	}
}
?>
