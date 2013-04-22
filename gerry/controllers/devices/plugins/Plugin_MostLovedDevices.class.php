<?php
class Plugin_MostLovedDevices extends Plugin
{
	public static $options = array(
		'display_limit:range:1,10'
	);

//		public static $link_location = array('datasheets', 'Datasheets_Browsing', 'browseLoved', array('#'=>'boxy'));

	public function skip_device()
	{
		Session::Set('skip_loved_device', $_GET['skip']);
	}

	public function fill_template(&$template)
	{
		$skip = 0;
		if (Session::Get('skip_loved_device'))
		{
			$skip = Session::Get('skip_loved_device');
		}
		$loved_devices = DBManager::Get()->skip($skip)->limit($this->get_config('display_limit'))->query("SELECT device_id, COUNT(youser_id) AS youser_count FROM like_devices WHERE lovehate = 'love' GROUP BY device_id ORDER BY COUNT(youser_id) DESC")->to_array('device_id', 'youser_count');
		$total = DBManager::Get()->query("SELECT COUNT(DISTINCT(device_id)) FROM like_devices WHERE lovehate = 'love';")->fetch_item();

		if (empty($loved_devices))
		{
			return false;
		}

		$template->assign('devices', $loved_devices);
		$template->assign('limit', $this->get_config('display_limit'));
		$template->assign('skip', $skip);
		$template->assign('total', $total);

		return true;
	}
}
?>