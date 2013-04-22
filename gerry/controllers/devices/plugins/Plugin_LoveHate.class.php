<?php
class Plugin_LoveHate extends Plugin
{
	public static $hooks = array(
		'Device:Deleted'=>'DeviceDeleted',
	);

	public static $options = array(
		'display_limit:range:1,40'
	);

	public function fill_template(&$template)
	{
		if (empty($_GET['device_id']))
			return false;

		$limit = $this->get_config('display_limit');

		$template->assign('links', array(
			'remove' => FrontController::GetLink('Plugin', 'LoveHate', 'RemoveDevice', array('device_id'=>$_GET['device_id'], 'return_to'=>FrontController::GetURL())),
		));
		$template->assign('device_id', $_GET['device_id']);
		$template->assign('lovers', DBManager::Get()->limit($limit)->query("SELECT youser_id FROM like_devices WHERE device_id=? AND lovehate = 'love' ORDER BY RAND(UNIX_TIMESTAMP())", $_GET['device_id'])->to_array(null, 'youser_id'));
		$template->assign('haters', DBManager::Get()->limit($limit)->query("SELECT youser_id FROM like_devices WHERE device_id=? AND lovehate = 'hate' ORDER BY RAND(UNIX_TIMESTAMP())", $_GET['device_id'])->to_array(null, 'youser_id'));
		$template->assign('quantities', DBManager::Query("SELECT lovehate, COUNT(*) AS quantity FROM like_devices WHERE device_id = ? GROUP BY lovehate", $_GET['device_id'])->to_array('lovehate', 'quantity'));
		if (Youser::Id())
			$template->assign('got_this_device', DBManager::Get()->query("SELECT 1 FROM like_devices WHERE youser_id=? AND device_id=?", Youser::Id(), $_GET['device_id'])->fetch_item());

		return true;
	}

	public function fill_template_nickpage(&$template)
	{
		$youser_id = isset($_REQUEST['youser_id'])
			? $_REQUEST['youser_id']
			: Youser::Id();

		if (empty($youser_id))
		{
			return false;
		}

		$devices = DBManager::Get()->query("SELECT device_id FROM like_devices WHERE youser_id=?", $youser_id)->to_array(null, 'device_id');

		if (empty($devices))
		{
			return false;
		}

		$template->assign('devices', $devices);
		return true;
	}

	public function AddDevice()
	{
		if(isset($_REQUEST['like']))
		{
			$status = $_REQUEST['like'];
		}
		Event::Dispatch('alert', 'Youser:'.$_REQUEST['like'], Youser::Id(), $_REQUEST['device_id']);
		DBManager::Get()->query("INSERT IGNORE INTO like_devices (youser_id, device_id, lovehate, timestamp) VALUES (?, ?, ?,  NOW())",
			Youser::Id(),
			$_GET['device_id'],
			$status
		);
	}

	public function RemoveDevice()
	{
		Event::Dispatch('alert', 'Youser:DeletedOpinion', Youser::Id(), $_REQUEST['device_id']);
		DBManager::Get()->query("DELETE FROM like_devices WHERE youser_id=? AND device_id=?",
			Youser::Id(),
			$_GET['device_id']
		);
	}

	public static function DeviceDeleted($device_id)
	{
		DBManager::Get()->query("DELETE FROM like_devices WHERE device_id=?", $device_id);
	}
}
?>