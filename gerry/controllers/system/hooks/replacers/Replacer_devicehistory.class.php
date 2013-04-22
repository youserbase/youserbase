<?php
class Replacer_devicehistory {
	const tag = 'devicehistory';

	private static $devices = array();

	private static function get_template()
	{
		return new Template(dirname(__FILE__).'/templates/'.self::tag.'.php');
	}

	public static function Prepare($matches)
	{
	}

	public static function Consume($match)
	{
		$history = (array) Session::Get('memory', 'devices');

		// Store device id in history if datasheet is viewed
		if (FrontController::IsLocation('Datasheets', 'datasheets', 'page'))
		{
			$history[$_REQUEST['device_id']] = time();

			arsort($history, SORT_DESC);

			Session::Set('memory', 'devices', $history);
		}

		if (empty($history))
		{
			return false;
		}

		$devices = Device::Get($history);

		$skip = Session::Get('history', 'skip')
			? $skip = Session::Get('history', 'skip')
			: 0;
		$limit = $match['parameters']['limit'];

		$device_ids = array_keys(array_slice($history, $skip, $limit));

		$template = self::get_template();
		$template->assign(compact('device_ids', 'skip', 'limit'));
		$template->assign('total', count($device_ids));
		return $template->render();
	}
}
?>