<?php
class Locale
{
	public static function Get()
	{
		$arguments = func_get_args();

		$value = isset($GLOBALS['LOCALES'][BabelFish::GetLanguage()])
			? $GLOBALS['LOCALES'][BabelFish::GetLanguage()]
			: reset($GLOBALS['LOCALES']);

		foreach ($arguments as $argument)
		{
			if (!isset($value[$argument]))
			{
				return null;
			}
			$value = $value[$argument];
		}
		return $value;
	}
}
?>