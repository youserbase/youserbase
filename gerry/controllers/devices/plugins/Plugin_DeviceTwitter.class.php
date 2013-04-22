<?php
class Plugin_DeviceTwitter extends Plugin
{
	public static $options = array(
		'display_maximum:range:1,100',
		'query_template:string:required',
		'cache_duration:range:5,300,5',
	);

	public function fill_template(&$template)
	{
		$device = Device::Get($_REQUEST['device_id']);
		$data = array(
			'language'=>BabelFish::GetLanguage(),
			'manufacturer' => BabelFish::Get($device['manufacturer']),
			'name' => BabelFish::Get($device['name']),
		);

		$query = Template::Interpolate($this->get_config('query_template'), $data);
		$feed_url = sprintf('http://search.twitter.com/search.json?language=%s&rpp=%u&q=%s',
			BabelFish::GetLanguage(),
			$this->get_config('display_maximum'),
			urlencode($query)
		);

		$cached_filename = Cache::GetDirectory('twitter').'/'.md5($feed_url);
		if (!file_exists($cached_filename) or (filemtime($cached_filename) + $this->get_config('cache_duration') < time()))
		{
			$contents = HTTP::Fetch($feed_url);
			if (!empty($contents))
			{
				file_put_contents($cached_filename, $contents);
			}
		}
		if (!isset($contents))
		{
			$contents = file_get_contents($cached_filename);
		}

		$twitter = json_decode($contents, true);

		if (empty($twitter['results']))
		{
			return false;
		}

		$tweets = array();
		foreach ($twitter['results'] as $tweet)
		{
			array_push($tweets, array(
				'content' => $tweet['text'],
				'timestamp' => strtotime($tweet['created_at']),
				'link' => 'https://twitter.com/'.$tweet['from_user'].'/statuses/'.$tweet['id'],
			));
		}
		$template->assign('tweets', $tweets);

		$random_id = md5(uniqid('twitter', true));
		$template->assign('random_id', $random_id);
		$template->register_filter('twitterfy', $random_id);
	}
}
?>