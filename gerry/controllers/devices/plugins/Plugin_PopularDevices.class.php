<?php
class Plugin_PopularDevices extends Plugin
{
	public static $options = array(
		'display_limit:range:1,10'
	);

	public function skip_devices()
	{
		if($_GET['skip'] < 0)
		{
			$_GET['skip'] = 0;
		}
		Session::Set('skip_best_devices', $_GET['skip']);
	}
	
//	public static $link_location = array('datasheets', 'Datasheets_Browsing', 'browseBest', array('#'=>'boxy'));

	public function fill_template(&$template)
	{
		/*
		$devices = DBManager::Get()
			->limit( $this->get_config('display_limit') )
			->query(
				"SELECT device_id
				 FROM statistics_daily_device_ranks
				 ORDER BY daystamp DESC, rank ASC, RAND()"
			)
			->to_array(null, 'device_id');*/
		$limit = $this->get_config('display_limit');
		$skip = 0;
		if (Session::Get('skip_best_devices'))
		{
			$skip = Session::Get('skip_best_devices');
		}
		if($skip < 0) $skip = 0;
		$devices = device_stats::get_stats($limit, $skip);
		//$total = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT(device_id)) FROM device_statistics")->fetch_item();
		if (empty($devices))
		{
			return false;
		}
		$template->assign('total', 1600);
		$template->assign('skip', $skip);
		$template->assign('limit', $limit);
		$template->assign('devices', array_flip($devices));

		return true;
	}
}
?>