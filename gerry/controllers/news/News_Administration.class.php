<?php
class News_Administration extends Controller
{
	public function Index()
	{
		$template = $this->get_template(true);
		$template->assign('feeds', DBManager::Get()->query("SELECT feed_id, url, link, title, description, language, image, status, UNIX_TIMESTAMP(last_update) AS last_update, failures FROM rss_feeds")->to_array());
	}

	public function Toggle()
	{
		if (isset($_REQUEST['feed_id']))
		{
			DBManager::Get()->query("UPDATE rss_feeds SET status=IF(status='enabled', 'disabled', 'enabled') WHERE feed_id=?",
				$_REQUEST['feed_id']
			);
		}

		FrontController::Relocate('Index');
	}

	public function Delete()
	{
		DBManager::Query("DELETE FROM rss_feeds WHERE feed_id=?", $_GET['feed_id']);
		DBManager::Get('cache')->query("DELETE FROM rss_entries WHERE feed_id=?", $_GET['feed_id']);
		FrontController::Relocate('Index');
	}

	public function Insert_POST()
	{
		$old_e = error_reporting(0);

		$feed = new SimplePie();
		$feed->set_feed_url($_POST['url']);
		$feed->init();

		$type = null;
		if ($feed->get_type() & SIMPLEPIE_TYPE_RSS_RDF)
		{
			$type = 'RDF';
		}
		elseif ($feed->get_type() & SIMPLEPIE_TYPE_RSS_ALL)
		{
			$type = 'RSS';
		}
		elseif ($feed->get_type() & SIMPLEPIE_TYPE_ATOM_ALL)
		{
			$type = 'Atom';
		}

		if (count($feed->get_items())==0)
		{
			Dobber::ReportError('Der Feed konnte nicht eingetragen werden (entweder leer oder ung�ltig');
			return;
		}

		$success = DBManager::Get()->query("INSERT IGNORE INTO rss_feeds (url, link, type, title, description, image, language, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'enabled')",
			$_POST['url'],
			$feed->get_permalink(),
			$type,
			$feed->get_title(),
			$feed->get_description(),
			$feed->get_favicon(),
			map_language(substr($feed->get_language(), 0, 2))
		);

		error_reporting($old_e);

		Dobber::ReportSuccess('Der Feed wurde erfolgreich eingetragen');
		FrontController::Relocate();
	}

	public function Insert()
	{
		$template = $this->get_template(true);
	}
}
?>