<?php
// TODO: Auf Cronjob und DB umschreiben
class Plugin_Twitter extends Plugin
{
	public static $options = array(
		'display_maximum:range:1,20',
		'twitter_username:string:required',
	);

	public static $cronjob = 60;
	public function cronjob()
	{
		$feed_url = 'http://twitter.com/statuses/user_timeline/'.$this->get_config('twitter_username').'.rss';
		$contents = HTTP::Fetch($feed_url);
		$tweets = simplexml_load_string($contents);

		foreach ($tweets->channel->item as $tweet)
		{
			$message = substr($tweet->title, strlen($this->get_config('twitter_username'))+2);
			DBManager::Get('cache')->query("INSERT IGNORE INTO tweets (tweet_id, author, message, message_formatted, link, timestamp) VALUES (?, ?, ?, ?, ?, FROM_UNIXTIME(?)) ON DUPLICATE KEY UPDATE message_formatted=VALUES(message_formatted), timestamp=VALUES(timestamp)",
				array_pop(explode('/', $tweet->guid)),
				$this->get_config('twitter_username'),
				$message,
				self::twitterfy(BoxBoy::Prepare($message)),
				''.$tweet->link,
				strtotime($tweet->pubDate)
			);
		}
	}

	public function fill_template(&$template)
	{
		$template->assign('username', $this->get_config('twitter_username'));
		$template->assign('display_follow', true);
		$template->assign('tweets', DBManager::Get('cache')->limit($this->get_config('display_maximum'))->query("SELECT message_formatted AS content, UNIX_TIMESTAMP(timestamp) AS timestamp, link FROM tweets WHERE author=? ORDER BY timestamp DESC", $this->get_config('twitter_username'))->to_array());
	}

	private static function twitterfy($string)
	{
		$matched = preg_match_all('/((?:https?|ftp):\/\/\S+)/', $string, $matches);

		if ($matched and count($matches[1]))
		{
			$urls = LongURL::Resolve($matches[1]);

			foreach ($matches[1] as $url)
				$replaces[$url] = (empty($urls[$url]) or $urls[$url]==$url)
					? '<a href="'.$url.'">'.$url.'</a>'
					: '<a href="'.$urls[$url].'" title="'.$urls[$url].'" class="resolved">'.$url.'</a>';

			$string = strtr($string, $replaces);
		}

		$replaces = array(
			'/(>|\s)?@([a-z0-9_-]+)/i' => '$1<a href="http://twitter.com/$2" class="nickpage">@$2</a>',
			'/(>|\s)#([^ <]+)/' => '$1<a href="http://search.twitter.com/search?q=#$2" class="tag">#$2</a>',
		);
		return preg_replace(array_keys($replaces), array_values($replaces), $string);
	}
}
?>