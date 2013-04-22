<?php
class Replacer_advertisement {
	const tag = 'advertisement';

	private static $mapping = array(
		'top' => 1,
		'skyscraper' => 2,
		'rbox' => 7,
		'datasheet' => 11,
	);
	private static $context = array();

	private static function get_template()
	{
		return new Template(dirname(__FILE__).'/templates/'.self::tag.'.php');
	}

	public static function Prepare($matches)
	{
		define('MAX_PATH', '/var/www/yb_ads');
	}

	public static function Consume($match)
	{
		if (empty(self::$mapping[ $match['parameters']['id'] ]))
			return false;

		if (Youser::May('zap'))
			return false;

		require_once MAX_PATH.'/www/delivery/alocal.php';

		$zone_id = self::$mapping[ $match['parameters']['id'] ];
		$ad = view_local('', $zone_id, 0, 0, '', '', '0', self::$context);

		array_push(self::$context, array('!=' => 'bannerid:'.$ad['bannerid']));
		array_push(self::$context, array('!=' => 'campaignid:'.$ad['campaignid']));

		// Reset db scope cause open-x fucked it up PRETTY BAD!!
		DBManager::Reset();

		return $ad['html'];
	}
}
?>