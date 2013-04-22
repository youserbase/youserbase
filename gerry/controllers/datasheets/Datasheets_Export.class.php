<?php
class Datasheets_Export extends Controller
{
	public function Comments()
	{
		$device_id = $_REQUEST['device_id'];
		$device_name = DBManager::Get('devices')->query("SELECT device_names_name FROM device_names WHERE device_id = ?;", $device_id)->fetch_item();
		$device_name = BabelFish::Get($device_name);
		$language = BabelFish::GetLanguage();
		$comments = comment_handler::get_comments($device_id, $language , 0, null, 'DESC', 2);
		$link = FrontController::GetAbsoluteURI().FrontController::GetLink('Datasheets', 'datasheets', 'page', array('device_id' => $device_id));


		$data = array('channel'=>array(
			'title' => $device_name,
			'link' => $link,
			'description' => 'Comments For '.$device_name,
			'language' => $language,
			'copyright' => 'youserbase.org',
			'pubDate' => gmdate("D, d M Y H:i:s")." GMT",
			/*'image' => array(
				'url' => '',
				'title' => '',
				'link' => '',
			),*/
		));

		foreach ($comments as $comment)
			array_push($data['channel'], array(
				'title' => $device_name,
				'description' => '<![CDATA['.htmlentities(utf8_decode($comment['comment'])).']]>',
				'link' => $link.'#Comments',
				'author' => 'rss@youserbase.org (youserbase.org)',
				'guid' => $link.'?comment_id='.$comment['comments_id'].'#Comments',
				'pubDate' => gmdate("D, d M Y H:i:s", $comment['timestamp']) . " GMT",
			));

		// Hack is needed for IE and Mozilla to avoid the download prompt
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')!==false)
			Header('Content-Type: text/xml');
		else
			Header('Content-Type: application/rss+xml');
		echo ShapeShifter::Transform($data, 'xml', array(
			'root_node' => 'rss',
			'root_attributes' => array('version' => '2.0'),
			'add_id_index' => false,
		));
		die;
	}
}
?>