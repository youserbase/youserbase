<?php
class Plugin_DeviceHistory extends Plugin
{
	public static $hooks = array(
		'DataSheet:Display'=>'AddDeviceToHistory',
	);

	public static $options = array(
		'display_limit:range:1,20'
	);

	public static $link_location = array('datasheets', 'Datasheets_Browsing', 'browseHistory', array('#'=>'boxy'));

	public function skip_device()
	{
		Session::Set('history', 'skip', $_GET['skip']);
	}

	public static function AddDeviceToHistory($device_id)
	{
		$history = Session::Get('history', 'devices')!==null
			? Session::Get('history', 'devices')
			: array();

		$history = array_diff($history, array($device_id));
		array_unshift($history, $device_id);

		Session::Set('history', 'devices', $history);
	}

	public function fill_template(&$template)
	{
		$skip = Session::Get('history', 'skip')
			? $skip = Session::Get('history', 'skip')
			: 0;
		$limit = $this->get_config('display_limit');

		$device_ids = Session::Get('history', 'devices');
		if(!empty($device_ids)){
			$device_ids = array_slice($this->$device_ids, $skip, $limit);
		}

		$template->assign(compact('device_ids', 'skip', 'limit'));
		$template->assign('total', count($device_ids));

		return true;
	}

	public function clean_history($history)
	{
		$clean_history = investigator::device_exists($history);
		Session::Set('history', 'devices', $clean_history);
		return $clean_history;
	}
}
?>