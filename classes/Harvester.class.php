<?php
class Harvester
{
	private static $instance = null;
	private static $crops = array();
	private static $type = null;

	private function __construct()
	{
		$crops = glob(dirname(__FILE__).'/crops/*.crop.php', GLOB_NOSORT);
		foreach ($crops as $crop)
		{
			$type = basename($crop, '.crop.php');
			include $crop;

			self::$crops[$type] = $crop;
		}
	}

	private static function &GetInstance()
	{
		if (self::$instance === null)
		{
			self::$instance =& new self;
		}
		return self::$instance;
	}

	public static function SetType($type)
	{
		self::$type = $type;

		if (!isset(self::$crops[self::$type]))
		{
			Header('HTTP/1.0 503 Not Implemented');
			echo '503 - Not implemented';
			die;
		}
	}
}
?>