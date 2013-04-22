<?php
class Plugin_CompareDevices extends Plugin
{
	public static $options = array(
		'display_limit:range:1,10'
	);

	public function fill_template(&$template)
	{
		if (!Session::Get('compare'))
		{
			return false;
		}

		$compare_devices = Session::Get('compare');

		$template->assign('devices', $compare_devices);

		return true;
	}

	public function add_device()
	{
		comparelist::add_device();
	}

	public function remove_device()
	{
		comparelist::remove_device();
	}
}
?>