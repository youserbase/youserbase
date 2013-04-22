<?php
class LongURL
{
	const version = '0.1';
	private static $cache = array();

	private static function FetchURL($url)
	{
		if (is_array($url))
		{
			$result = array();
			foreach ($url as $uri)
			{
				$result[$uri] = self::FetchURL($uri);
			}
			return $result;
		}

		$api_url = 'http://api.longurl.org/v2/expand?url='.urlencode($url).'&format=php';
		$response = HTTP::Fetch($api_url, 'yb uri resolver v'.self::version.' (http://youserbase.org)');
		if (!$response)
		{
			return false;
		}

		$result = unserialize($response);
		return isset($result['long-url'])
			// I know, it looks weird but this way we easily get rid of all stand-alone ampersands
			? str_replace('&', '&amp;', str_replace('&amp;', '&', $result['long-url']))
			: null;
	}

	public static function Resolve($url)
	{
		$urls = (array)$url;
		$result = array();

		$cached = array_intersect($urls, array_keys(self::$cache));
		$urls = array_diff($urls, $cached);

		$known_urls = empty($urls)
			? array()
			: DBManager::Get('cache')->query("SELECT shorturl, url FROM longurls WHERE shorturl IN (?)", $urls)->to_array('shorturl', 'url');
		self::$cache = array_merge(self::$cache, $known_urls);
		$cached = array_merge($cached, array_keys($known_urls));
		$urls = array_diff($urls, array_keys($known_urls));

		$fetched_urls = self::FetchURL($urls);
		foreach ($fetched_urls as $short=>$long)
		{
			DBManager::Get('cache')->query("INSERT INTO longurls (shorturl, url, timestamp) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE url=VALUES(url), timestamp=VALUES(timestamp)", $short, $long);
		}
		self::$cache = array_merge(self::$cache, $fetched_urls);
		$cached = array_merge($cached, array_keys($fetched_urls));

		foreach ($cached as $uri)
		{
			$result[$uri] = self::$cache[$uri];
		}

		return is_array($url)
			? $result
			: reset($result);
	}
}
?>