<?php
class Plugin_DeviceSimilarity extends Plugin
{
	public static $options = array(
		'display_limit:range:1,10'
	);

	public static $link_location = array('datasheets', 'Datasheets_Browsing', 'browseSimilar', array('device_id'=>'#{device_id}', '#'=>'boxy'));

	public function skip_device()
	{
		Session::Set('similar_devices', 'skip', $_GET['skip']);
	}

	public function fill_template(&$template)
	{
		$limit = $this->get_config('display_limit');

		if (!isset($_GET['device_id']))
		{
			return false;
		}

		$skip = 0;
		if (Session::Get('similar_devices', 'skip'))
		{
			$skip = Session::Get('similar_devices', 'skip');
		}

		$similar_devices = investigator::getSimilarDevices($_GET['device_id'], $skip, $limit);

		if (empty($similar_devices))
		{
			return false;
		}

		$template->assign('similarity', $similar_devices);
		$template->assign('device_id', $_GET['device_id']);
		$template->assign('skip', $skip);
		$template->assign('limit', $this->get_config('display_limit'));
		$template->assign('total', DeviceHelper::GetDeviceCount());

		return true;
	}
}
?>