<?php
class Plugin_News extends Plugin
{
	public static $options = array(
		'display_limit:range:1,50',
		'wrap_limit:range:0,250'
	);

	public static $cronjob = 300;
	public function cronjob()
	{
		$feeds = DBManager::Get()->query("SELECT feed_id, url FROM rss_feeds WHERE status='enabled'")->to_array('feed_id', 'url');

		foreach ($feeds as $feed_id => $url)
		{
			$feed = RSS::Read($url);
			if (!$feed)
			{
				DBManager::Get()->query("UPDATE rss_feeds SET failures=failures+1 WHERE feed_id=?", $feed_id);
				continue;
			}

			DBManager::Get()->query("UPDATE rss_feeds SET failures=0, link=?, type=?, title=?, description=?, image=?, language=IFNULL(language, ?), last_update=NOW() WHERE feed_id=?",
				$feed['link'],
				$feed['type'],
				$feed['title'],
				$feed['description'],
				$feed['image'],
				$feed['language'],
				$feed_id
			);

			foreach ($feed['items'] as $item)
			{
				DBManager::Get('cache')->query("INSERT INTO rss_entries (feed_id, hash, title, description, content, author, url, timestamp, last_updated) VALUES (?, ?, TRIM(?), TRIM(?), TRIM(?), TRIM(?), ?, IFNULL(FROM_UNIXTIME(?), NOW()), NOW()) ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), content=VALUES(content), author=VALUES(author), timestamp=VALUES(timestamp), last_updated=NOW()",
					$feed_id,
					$item['id'],
					$item['title'],
					strip_tags(preg_replace('/<br\s*\/?>/', "\n", $item['description'])),
					$item['content'],
					$item['author'],
					$item['link'],
					$item['timestamp']
				);
			}
		}
		DBManager::Get()->query("UPDATE rss_feeds SET status='disabled' WHERE failures>5");
	}

	public function fill_template(&$template)
	{
		$limit = $this->get_config('display_limit');
		$wrap = $this->get_config('wrap_limit');

		$feed_ids = DBManager::Get()->query("SELECT feed_id FROM rss_feeds WHERE status='enabled' AND language=?", BabelFish::GetLanguage())->to_array(null, 'feed_id');

		if (!empty($feed_ids))
		{
			$news = DBManager::Get('cache')->limit($limit)->query("SELECT entry_id, feed_id, title, description, UNIX_TIMESTAMP(timestamp) AS timestamp FROM rss_entries WHERE feed_id IN (?) ORDER BY timestamp DESC", $feed_ids)->to_array();
		}
		if (empty($news))
		{
			$feed_ids = DBManager::Get()->query("SELECT feed_id FROM rss_feeds WHERE status='enabled' AND language IN ('en', 'us', 'uk')")->to_array(null, 'feed_id');
			if (!empty($feed_ids))
			{
				$news = DBManager::Get('cache')->limit($limit)->query("SELECT entry_id, feed_id, title, description, UNIX_TIMESTAMP(timestamp) AS timestamp FROM rss_entries WHERE feed_id IN (?) ORDER BY timestamp DESC", $feed_ids)->to_array();
			}
		}

		$template->assign('news', $news);
		$template->assign('feeds', DBManager::Get()->query("SELECT feed_id, title, image, link FROM rss_feeds")->to_array('feed_id'));
		$template->assign('wrap_limit', $wrap);
	}
}
?>