<?php
class Location
{
	private static $query,
		$module = 'System',
		$controller = 'System',
		$method = 'Index',
		$parameters;

	function __construct()
	{
		$arguments = func_get_args();
	}

	public static function Parse($string)
	{
		$location = array(
			'module' => self::$module,
			'controller' => self::$controller,
			'method' => self::$method,
			'parameters' => array(),
		);

		$url_parts = explode('?', $string, 2);
		if (!empty($url_parts[0]))
		{
			$temp_location = explode('/', $url_parts[0]);

			$location = array(
				'module' => $temp_location[0],
				'controller' => $temp_location[1],
				'method' => $temp_location[2],
				'parameters' => array(),
			);
		}

		if (!empty($url_parts[1]))
		{
			parse_str($url_parts[1], $arguments);
			$location['parameters'] = $arguments;
		}
		return $location;
	}
}
?>