<?php
class Plugin_RelatedDevices extends Plugin
{
	public static $options = array(
		'display_limit:range:1,10'
	);

	public function skip_device()
	{
		Session::Set('skip_related_device', $_GET['skip']);
	}

	public function fill_template(&$template)
	{
		return false;
		$limit = $this->get_config('display_limit');

		if (!isset($_GET['device_id']))
		{
			return false;
		}

		$skip = 0;
		if(Session::Get('skip_related_device'))
		{
			$skip = Session::Get('skip_related_device');
		}

		$child_devices = investigator::getChildDevices($_GET['device_id'], $skip, $limit);

		if (empty($child_devices))
		{
			return false;
		}

		$template->assign('devices', $child_devices);
		$template->assign('device_id', $_GET['device_id']);
		$template->assign('skip', $skip);

		return true;
	}
}
?>