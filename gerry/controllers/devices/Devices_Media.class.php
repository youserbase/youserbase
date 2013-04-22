<?php
class Devices_Media extends Controller
{
	public function Video()
	{
		$videos = DBManager::Get()->query("SELECT media_id, title, description, media_key, source, author, UNIX_TIMESTAMP(media_timestamp) AS media_timestamp, user_id, UNIX_TIMESTAMP(timestamp) AS timestamp FROM device_media WHERE device_id=? AND type='video' ORDER BY media_timestamp DESC", $_REQUEST['device_id'])->to_array('media_id');

		$current = isset($_REQUEST['media_id'])
			? $_REQUEST['media_id']
			: array_shift(array_keys($videos));

		$template = $this->get_template(true);
		$template->assign('device_id', $_REQUEST['device_id']);
		$template->assign('tab', isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '');
		$template->assign(compact('videos', 'current'));
	}
	
	public function All_Videos()
	{
		$videos = DBManager::Get()->query("SELECT device_id, media_id, title, description, media_key, source, author, UNIX_TIMESTAMP(media_timestamp) AS media_timestamp, user_id, UNIX_TIMESTAMP(timestamp) AS timestamp FROM device_media WHERE type='video' ORDER BY media_timestamp DESC")->to_array('media_id');
		
		$current = isset($_REQUEST['media_id'])
			? $_REQUEST['media_id']
			: array_shift(array_keys($videos));
		
		$template = $this->get_template(true);
		$template->assign(compact('videos', 'current'));
	}

	public function Add_POST()
	{
		$device_id = $_REQUEST['device_id'];
		$url = $_REQUEST['url'];
		if (strpos($url, 'youtube.com')!==false)
		{
			parse_str(parse_url($url, PHP_URL_QUERY), $parameters);
			$youtube_id = $parameters['v'];

			if (empty($youtube_id))
			{
				Dobber::ReportError('UNKNOWN_FILE_FORMAT');
				return false;
			}

			$xml = simplexml_load_string(HTTP::Fetch('http://gdata.youtube.com/feeds/base/videos/'.$youtube_id));

			DBManager::Get()->query("INSERT INTO device_media (device_id, type, source, title, url, media_key, media_timestamp, author, user_id, timestamp) VALUES (?, 'video', 'youtube', ?, ?, ?, FROM_UNIXTIME(?), ?, ?, NOW())",
				$device_id,
				$_REQUEST['title'],
				$url,
				$youtube_id,
				strtotime((string)$xml->published),
				$_REQUEST['author'],
				Youser::Id()
			);
			$media_id = DBManager::Get()->get_inserted_id();
			Event::Dispatch('alert', 'Device:Media:Added', $device_id, $media_id);
			Dobber::ReportSuccess('MEDIA_ADDED_SUCCESSFULLY', 'video (youtube)');
		}
		FrontController::Relocate('Datasheets', 'datasheets', 'page', array('device_id'=>$device_id, 'media_id'=>$media_id, 'tab'=>'Media'));
	}

	public function Add()
	{
		$template = $this->get_template(true);
		$template->assign('device_id', $_REQUEST['device_id']);
	}

	public function Edit_POST()
	{
		$title = !empty($_POST['title']) ? $_POST['title'] : null;
		DBManager::Get()->query("UPDATE device_media SET title=? WHERE device_id=? AND media_id=?",
			$title,
			$_REQUEST['device_id'],
			$_REQUEST['media_id']
		);
		Dobber::ReportSuccess('MEDIA_UPDATED');
		Lightbox::Close();
		FrontController::Relocate('Datasheets', 'datasheets', 'page', array('device_id'=>$_REQUEST['device_id'], 'media_id'=>$_REQUEST['media_id'], 'tab'=>'Media'));
		return false;
	}

	public function Edit()
	{
		$media = DBManager::Get()->query("SELECT title, description FROM device_media WHERE device_id=? AND media_id=?", $_REQUEST['device_id'], $_REQUEST['media_id'])->fetch_array();

		$template = $this->get_template(true);
		$template->assign($media);
		$template->assign('device_id', $_REQUEST['device_id']);
		$template->assign('media_id', $_REQUEST['media_id']);
	}

	public function Delete()
	{
		DBManager::Get()->query("DELETE FROM device_media WHERE device_id=? AND media_id=?", $_REQUEST['device_id'], $_REQUEST['media_id']);
		Dobber::ReportSuccess('MEDIA_DELETED');
		FrontController::Relocate('Datasheets', 'datasheets', 'page', array('device_id'=>$_REQUEST['device_id'], 'tab'=>'Media'));
	}
}
?>