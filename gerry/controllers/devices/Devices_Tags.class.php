<?php
class Devices_Tags extends Controller
{
	public function Index()
	{
		$template = $this->get_template(true);
		$template->assign('popular_tags', DBManager::Get()->limit(10)->query("SELECT tag, COUNT(*) AS quantity FROM youser_device_tags GROUP BY tag ORDER BY quantity DESC")->to_array('tag', 'quantity'));
		$template->assign('latest_tags', DBManager::Get()->limit(10)->query("SELECT DISTINCT tag, UNIX_TIMESTAMP(timestamp) AS timestamp FROM youser_device_tags ORDER BY timestamp DESC")->to_array('tag', 'timestamp'));
		$template->assign('most_tagged_devices', DBManager::Get()->limit(10)->query("SELECT device_id, COUNT(*) AS quantity FROM youser_device_tags GROUP BY device_id ORDER BY quantity DESC")->to_array('device_id', 'quantity'));
	}

	public function Tag()
	{
		// TODO: Tag nicht übergeben?

		$template = $this->get_template(true);
		$template->assign('tag', $_GET['tag']);
		$template->assign('devices', DBManager::Get()->query("SELECT device_id, COUNT(*) AS quantity FROM youser_device_tags WHERE tag = ? GROUP BY device_id", $_GET['tag'])->to_array('device_id', 'quantity'));
	}
}
?>