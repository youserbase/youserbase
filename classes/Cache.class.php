<?php
class Cache
{
	const CACHE_DIR = '../cache/';

	public static function GetDirectory($directory = null)
	{
		if ($directory===null)
		{
			return self::CACHE_DIR;
		}
		$path = realpath(dirname(__FILE__).'/'.self::CACHE_DIR).'/'.rtrim($directory, '/');
		if (!file_exists($path))
		{
			mkdir($path);
			chmod($path, 0777);
		}
		return $path;
	}

	public static function Exists()
	{
		$arguments = func_get_args();
		return file_exists(self::GetFilename($arguments))
			? filemtime(self::GetFilename($arguments))
			: false;
	}

	public static function Store()
	{
		$arguments = func_get_args();
		$contents = array_pop($arguments);

		// Delete old cached file, if any
		$old_filename = preg_replace('/(\.\d+)$/', '.*', self::GetFilename($arguments));
		array_map('unlink', glob($old_filename, GLOB_NOSORT));

		file_put_contents(self::GetFilename($arguments), $contents);
	}

	public static function Get()
	{
		$arguments = func_get_args();
		return file_get_contents(self::GetFilename($arguments));
	}

	public static function Remove()
	{
		$arguments = func_get_args();
		unlink(self::GetFilename($arguments));
	}

	private static function Hash($values)
	{
		$timestamp = array_pop($values);
		$values = array_map(create_function('$a', 'return is_string($a) ? $a : serialize($a);'), $values);
		return md5(implode('#|#', $values)).'.'.$timestamp;
	}

	private static function GetFilename($values)
	{
		return realpath(dirname(__FILE__).'/'.self::CACHE_DIR).'/'.self::Hash($values);
	}

	public static function GetRandomFilename()
	{
		return realpath(dirname(__FILE__).'/'.self::CACHE_DIR).'/'.md5(uniqid('random', true));
	}
}
?>