<?php
class Replacer_pagination {
	const tag = 'pagination';
	private static $required_parameters = array(
		'href',
		'current_page',
		'total',
		'max',
	);

	private static function get_template()
	{
		return new Template(dirname(__FILE__).'/templates/'.self::tag.'.php');
	}

	public static function Consume($match)
	{
		$parameters_missing = array_diff(self::$required_parameters, array_keys($match['parameters']));
		$replace = sprintf('<blockquote><b>Pagination compilation failed (missing attributes [%s])!</b><br/><pre>%s</pre></blockquote>',
			$parameters_missing,
			htmlentities($match['tag'])
		);

		if (empty($parameters_missing))
		{
			$replace = self::get_template()->assign($match['parameters'])->render();
		}

		return $replace;
	}
}
?>