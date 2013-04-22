<?php
class HTTP
{
	public static function Fetch($url, $user_agent = null, $referer = null)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_USERAGENT, ($user_agent !== null)
			? $user_agent
			: 'yb http fetcher (http://youserbase.org)'
		);
		curl_setopt($ch, CURLOPT_REFERER, ($referer !== null)
			? $referer
			: 'http://www.youserbase.org'
		);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}
}
?>