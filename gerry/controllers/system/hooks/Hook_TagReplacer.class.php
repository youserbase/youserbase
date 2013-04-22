<?php
class Hook_TagReplacer extends Hook
{
	public static $hooks = array(
		'Template:Fetch'=>array(
			'ReplaceTags',
		),
	);

	private static $replacers = null;


	public static function SetReplacers($replacers)
	{
		self::$replacers = $replacers;
	}

	public static function GetReplacers()
	{
		if (self::$replacers === null)
		{
			self::$replacers = array();

			foreach (glob(dirname(__FILE__).'/replacers/Replacer_*.class.php', GLOB_NOSORT) as $replacer)
			{
				$tag = preg_replace('/^.*Replacer_(.*)\.class\.php$/', '$1', $replacer);

				require_once $replacer;

				array_push(self::$replacers, $tag);
			}
		}
		return self::$replacers;
	}

	public static function ReplaceTags($content)
	{
		$replacers = self::GetReplacers();

		if (empty($replacers))
			return $content;

		Timer::Report('Replacer: Parse');
		$results = TagParser::Parse($content, $replacers);
		Timer::Report('Replacer: Parsed');

		while (count($results))
		{
			$replacements = array();
			foreach ($results as $tag => $result)
			{
				$prepare = array('Replacer_'.$tag, 'Prepare');
				if (is_callable($prepare) and false === (call_user_func($prepare, $result)))
					continue;

				Timer::Report('Replacer: Start '.$tag);
				foreach ($result as $match)
					$replacements[ $match['tag'] ] = call_user_func(array('Replacer_'.$tag, 'Consume'), $match);
				Timer::Report('Replacer: Stop '.$tag);
			}
			$content = str_replace(array_keys($replacements), array_values($replacements), $content);

			unset($replacements);

			$results = TagParser::Parse($content, $replacers);
		}

		return $content;
	}
}
?>