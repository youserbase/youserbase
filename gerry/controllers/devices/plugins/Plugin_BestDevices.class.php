<?php
class Plugin_BestDevices extends Plugin
{
	public static $options = array(
		'display_limit:range:1,10'
	);

//	public static $link_location = array('datasheets', 'Datasheets_Browsing', 'browseBest', array('#'=>'boxy'));

	public function skip_device()
	{
		Session::Set('skip_best_device', $_GET['skip']);
	}

	public function fill_template(&$template)
	{
		$skip = 0;
		if (Session::Get('skip_best_device'))
		{
			$skip = Session::Get('skip_best_device');
		}

		$best_devices = investigator::getBestDevices($skip, $this->get_config('display_limit'));

		if (empty($best_devices))
		{
			return false;
		}

		$template->assign('devices', $best_devices);
		$template->assign('limit', $this->get_config('display_limit'));
		$template->assign('skip', $skip);
		$template->assign('total', DeviceHelper::GetDeviceCount());

		return true;
	}
}
?>