<?php
	require '../../classes/vendor/simplepie.inc';
	require '../../includes/classloader.inc.php';
	require '../../includes/config.inc.php';

	error_reporting(E_ALL);

	$feeds = DBManager::Get()->query("SELECT feed_id, url FROM rss_feeds WHERE status='enabled'")->to_array('feed_id', 'url');

	foreach ($feeds as $feed_id=>$url)
	{
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->enable_cache(true);
		$feed->set_cache_location(Cache::GetDirectory('simplepie'));
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
			DBManager::Get()->query("UPDATE rss_feeds SET failures=failures+1 WHERE feed_id=?", $feed_id);
		}
		else
		{
			$success = DBManager::Get()->query("UPDATE rss_feeds SET failures=0, link=?, type=?, title=?, description=?, image=?, language=IF(language IS NOT NULL, language, ?), last_update=NOW() WHERE feed_id=?",
				$feed->get_permalink(),
				$type,
				$feed->get_title(),
				$feed->get_description(),
				$feed->get_favicon(),
				substr($feed->get_language(), 0, 2),
				$feed_id
			);

			foreach ($feed->get_items() as $item)
			{
				$success = DBManager::Get()->query("INSERT INTO rss_entries (feed_id, hash, title, description, content, author, url, timestamp, last_updated) VALUES (?, ?, TRIM(?), TRIM(?), TRIM(?), TRIM(?), ?, FROM_UNIXTIME(?), NOW()) ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description), content=VALUES(content), author=VALUES(author), timestamp=VALUES(timestamp), last_updated=NOW()",
					$feed_id,
					$item->get_id(true),
					$item->get_title(),
					strip_tags(preg_replace('/<br\s*\/?>/', "\n", $item->get_description())),
					$item->get_content(),
					$item->get_author()
						? $item->get_author()->get_name()
						: null,
					$item->get_permalink(),
					$item->get_date('U')
				);
			}
		}

		$feed->__destruct();
		unset($feed);
	}

	DBManager::Get()->query("UPDATE rss_feeds SET status='disabled' WHERE failures>5");
?>