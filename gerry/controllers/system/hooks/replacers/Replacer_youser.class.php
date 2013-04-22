<?php
class Replacer_youser {
	const tag = 'youser';
	private static $yousers = array();

	private static function get_template()
	{
		return new Template(dirname(__FILE__).'/templates/'.self::tag.'.php');
	}

	public static function Prepare($matches)
	{
		$ids = array_map(create_function('$a', 'return $a["parameters"]["id"];'), $matches);

		self::$yousers = DBManager::Get()->query(
			"SELECT youser_id AS id, nickname, visible-1 AS visible
			 FROM yousers
			 WHERE youser_id IN (?)",
			array_unique($ids)
		)->to_array('id');
	}

	public static function Consume($match)
	{
		if (!isset(self::$yousers[ $match['parameters']['id'] ]))
		{
			return '<phrase id="UNKNOWN_YOUSER"/>';
		}
		$youser = self::$yousers[ $match['parameters']['id'] ];
		$replace = BoxBoy::Prepare($youser['nickname']);

		if (!empty($match['parameters']['image']))
		{
			$replace = sprintf('<img src="%s" alt="%s" class="tipsify south %s" title="%s"/>',
				Youser_Image::GetLink($youser['id'], $match['parameters']['image']),
				$replace,
				$match['parameters']['image'],
				$replace
			);
		}
		elseif (!empty($match['parameters']['type']))
		{
			$replace = sprintf('<img src="%s" alt="%s" class="tipsify south %s" title="%s"/>',
				Youser_Image::GetLink( $youser['id'], $match['parameters']['type'] ),
				$replace,
				$match['parameters']['type'],
				$replace
			);
		}
		elseif (!empty($match['parameters']['highlight']))
		{
			$replace = preg_replace('/('.preg_quote($match['parameters']['highlight']).')/iS', '<span class="highlight">$1</span>', $replace);
		}

		if ((empty($match['parameters']['link']) or as_boolean($match['parameters']['link'])) and ($youser['visible'] or (!empty($match['parameters']['force']) and as_boolean($match['parameters']['force']))))
		{
			$replace = sprintf('<a href="%s">%s</a>',
				YouserHelper::GetLink($youser['id']),
				$replace
			);
		}

		return $replace;
	}
}
?>