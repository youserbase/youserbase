<?php
class ClassLoader
{
	private static $classes = null;
	private static $directories = array();

	public static function GetClasses()
	{
		return self::$classes;
	}

	public static function SetClasses($classes)
	{
		self::$classes = $classes;
	}

	public static function Load($class_name)
	{
		if (self::$classes === null)
		{
			self::$classes = array();
			foreach (self::$directories as $directory)
				self::$classes = array_merge(self::$classes, self::PrimeCache($directory));
		}

		if (!isset(self::$classes[strtolower($class_name)]))
			return false;

		require self::$classes[strtolower($class_name)];
		return true;
	}

	public static function AddDirectory($directory)
	{
		array_push(self::$directories, $directory);
	}

	private static function PrimeCache($directory)
	{
		$temp = glob($directory.'/*.class.php', GLOB_NOSORT);

		$directories = glob($directory.'/*', GLOB_ONLYDIR);
		foreach ($directories as $directory)
		{
			if (preg_match('/\/(?:css|templates|js)$/', $directory))
			{
				continue;
			}
			$temp = array_merge($temp, self::PrimeCache($directory));
		}

		$result = array();
		foreach ($temp as $file)
		{
			$result[strtolower(basename($file, '.class.php'))] = $file;
		}

		unset($temp);

		return $result;
	}
}
?>