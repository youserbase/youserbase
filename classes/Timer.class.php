<?php
class Timer
{
	private static $events = array();

	public static function Report($message)
	{
		$arguments = func_get_args();
		$message = array_shift($arguments);
		self::$events[(string)round((double)microtime(true), 15)] = vsprintf($message, $arguments);
	}

	public static function GetEvents()
	{
		static $iteration = 0;

		self::Report('__GETEVENTS (%d)', $iteration++);

		$events = array();
		$min = (double)min(array_keys(self::$events));
		foreach (self::$events as $timestamp => $message)
		{
			$events[(string)round((double)$timestamp - $min, 15)] = $message;
		}

		return $events;
	}

	public static function GetDuration()
	{
		$events = self::GetEvents();
		return (double)max(array_keys($events)) - (double)min(array_keys($events));
	}
}
?>