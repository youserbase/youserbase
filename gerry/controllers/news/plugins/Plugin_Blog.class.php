<?php
class Plugin_Blog extends Plugin
{
	public static $options = array(
		'feed_url:string:required',
		'display_maximum:range:1,20',
	);

	public static $link_location = 'http://www.youserblog.com/';

	public static $cronjob = 300;
	public function cronjob()
	{
		if (!$this->get_config('feed_url'))
		{
			return;
		}

		$feed = RSS::Read($this->get_config('feed_url'));
		$blog_id = self::GetBlogId($this->get_config('feed_url'));

		DBManager::Get('cache')->query("UPDATE blogs SET title=?, description=?, language=? WHERE blog_id=?",
			$feed['title'],
			$feed['description'],
			$feed['language'],
			$blog_id
		);

		foreach ($feed['items'] as $item)
		{
			DBManager::Get('cache')->query("INSERT INTO feed_items (item_id, blog_id, guid, title, message, link, timestamp) VALUES (NULL, ?, ?, TRIM(?), TRIM(?), ?, FROM_UNIXTIME(?)) ON DUPLICATE KEY UPDATE title=VALUES(title), message=VALUES(message), link=VALUES(link), timestamp=VALUES(timestamp)",
				$blog_id,
				$item['id'],
				$item['title'],
				$item['content'],
				$item['link'],
				$item['timestamp']
			);
		}
	}

	public function fill_template(&$template)
	{
		$blog_id = self::GetBlogId($this->get_config('feed_url'));

		$template->assign('items', DBManager::Get('cache')->limit($this->get_config('display_maximum'))->query("SELECT item_id, title, link, UNIX_TIMESTAMP(timestamp) AS timestamp FROM feed_items WHERE blog_id=? ORDER BY timestamp DESC", $blog_id)->to_array('item_id'));
	}

	private static function GetBlogId($feed_url)
	{
		$blog_id = DBManager::Get('cache')->query("SELECT blog_id FROM blogs WHERE feed_url=?", $feed_url)->fetch_item();
		if (!$blog_id)
		{
			DBManager::Get('cache')->query("INSERT INTO blogs (feed_url) VALUES (?)", $feed_url);
			$blog_id = DBManager::Get('cache')->get_inserted_id();
		}
		return $blog_id;
	}
}
?>