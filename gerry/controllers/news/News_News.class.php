<?php
class News_News extends Controller
{
	public function Read()
	{
		if (empty($_REQUEST['news_id']))
		{
			Dobber::ReportError('INVALID_REQUEST');
			return;
		}

		$news = DBManager::Get('cache')->query("SELECT feed_id, title, content, author, url, UNIX_TIMESTAMP(timestamp) AS timestamp FROM rss_entries WHERE entry_id=?", $_REQUEST['news_id'])->fetch_item(array());
		$feed = DBManager::Get()->query("SELECT title, image FROM rss_feeds WHERE feed_id=?", $news['feed_id'])->fetch_item(array());

		$template = $this->get_template(true);
		$template->assign('news_id', $_REQUEST['news_id']);
		$template->assign('feed', $feed);
		$template->assign('news', $news);
	}
}
?>