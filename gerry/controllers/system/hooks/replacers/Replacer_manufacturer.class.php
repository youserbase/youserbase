<?php
class Replacer_manufacturer {
	const tag = 'manufacturer';
	private static $manufacturer_data = array();

	private static function get_template()
	{
		return new Template(dirname(__FILE__).'/templates/'.self::tag.'.php');
	}

	public static function Prepare($matches)
	{
		// Get ids from matches
		$ids = array_map(create_function('$a', 'return $a["parameters"]["id"];'), $matches);

		// Prime manufacturer cache
		$data = Manufacturer_Name::Get($ids);

		// Get manufacturer name phrases
		$names = array_map(create_function('$a', 'return $a["name"];'), $data);

		// Prime Babelfish cache
		BabelFish::Get( $names );
	}

	public static function Consume($match)
	{
		$manufacturer = Manufacturer_Name::Get($match['parameters']['id']);

		if (!$manufacturer)
		{
			return '<phrase id="UNKNOWN_MANUFACTURER"/>';
		}

		$template = self::get_template();
		$template->assign($manufacturer);
		$template->assign('image', empty($match['parameters']['image']) ? false : $match['parameters']['image']);
		$template->assign('link', empty($match['parameters']['link']) or as_boolean($match['parameters']['link']));

		return $template->render();
	}
}
?>