<?php
class Replacer_plugin {
	const tag = 'plugin';
	private static $required_parameters = array(
		'id',
	);

	private static function get_template()
	{
		return new Template(dirname(__FILE__).'/templates/'.self::tag.'.php');
	}

	public static function Consume($match)
	{
		return PluginEngine::Engage($match['parameters']['id']);
	}
}
?>