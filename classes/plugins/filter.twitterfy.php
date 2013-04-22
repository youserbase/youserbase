<?php
function filter_twitterfy($string, $id)
{
	$string = str_replace(array('<br/>', '<br />', '<br>'), '', $string);

	$matched = preg_match_all('/<twitterfy:'.$id.'>.*?((?:https?|ftp):\/\/\S+).*?<\/twitterfy:'.$id.'>/', $string, $matches);

	if ($matched)
	{
		$urls = LongURL::Resolve($matches[1]);

		$replaces = array(
			'<twitterfy:'.$id.'>' => '',
			'</twitterfy:'.$id.'>' => '',
		);
		foreach ($matches[1] as $url)
		{
			$replaces[$url] = empty($urls[$url])
				? '<a href="'.$url.'">'.$url.'</a>'
				: '<a href="'.$urls[$url].'" title="'.$urls[$url].'" class="resolved">'.$url.'</a>';
		}

		$string = strtr($string, $replaces);
	}

	$replaces = array(
		'/(>|\s)@([a-z0-9_-]+)/i' => '$1<a href="http://twitter.com/$2" class="nickpage">@$2</a>',
		'/(>|\s)#([^ <]+)/' => '$1<a href="http://search.twitter.com/search?q=#$2" class="tag">#$2</a>',
	);
	return preg_replace(array_keys($replaces), array_values($replaces), $string);
}
?>