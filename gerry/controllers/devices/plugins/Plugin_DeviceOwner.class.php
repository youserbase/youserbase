<?php
class Plugin_DeviceOwner extends Plugin
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

		$template->assign('device_id', $_GET['device_id']);
		$template->assign('yousers', DBManager::Get()->limit($limit)->query("SELECT youser_id FROM youser_devices WHERE device_id=? ORDER BY RAND(UNIX_TIMESTAMP())", $_GET['device_id'])->to_array(null, 'youser_id'));
		$template->assign('youser_count', DBManager::Query("SELECT COUNT(*) FROM youser_devices WHERE device_id = ?", $_GET['device_id'])->fetch_item());
		if (Youser::Id())
			$template->assign('got_this_device', DBManager::Get()->query("SELECT 1 FROM youser_devices WHERE youser_id=? AND device_id=?", Youser::Id(), $_GET['device_id'])->fetch_item());

		return true;
	}

	public function fill_template_nickpage(&$template)
	{
		$youser_id = isset($_REQUEST['youser_id'])
			? $_REQUEST['youser_id']
			: Youser::Id();

		if (empty($youser_id))
			return false;

		$devices = DBManager::Get()->query("SELECT device_id FROM youser_devices WHERE youser_id=?", $youser_id)->to_array(null, 'device_id');

		if (empty($devices))
			return false;

		$template->assign('devices', $devices);
		return true;
	}

	public function AddDevice()
	{
		Event::Dispatch('alert', 'Youser:OwnsDevice', Youser::Id(), $_GET['device_id']);
		DBManager::Get()->query("INSERT IGNORE INTO youser_devices (youser_id, device_id, timestamp) VALUES (?, ?, NOW())",
			Youser::Id(),
			$_GET['device_id']
		);
	}

	public function RemoveDevice()
	{
		Event::Dispatch('alert', 'Youser:NoLongerOwnsDevice', Youser::Id(), $_GET['device_id']);
		DBManager::Get()->query("DELETE FROM youser_devices WHERE youser_id=? AND device_id=?",
			Youser::Id(),
			$_GET['device_id']
		);
	}

	public static function DeviceDeleted($device_id)
	{
		DBManager::Get()->query("DELETE FROM youser_devices WHERE device_id=?", $device_id);
	}
}
?>