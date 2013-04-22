<?php
class TagParser
{
	public static function Parse($string, $tag, $primary_key=null)
	{
		$start_time = microtime(true);

		$tags = ($return_array = is_array($tag))
			? $tag
			: array($tag);

		$regexp = '/<('.implode('|', $tags).')(\s[^>]*)?\/>/Su';

		preg_match_all($regexp, $string, $matches, PREG_SET_ORDER);

		$result = array();
		foreach ($matches as $match)
		{
			$item = array(
				'tag'=>$match[0],
				'parameters'=>empty($match[2])
					? array()
					: self::ParseParameters($match[2]),
				'content'=>null,
			);

			if (!isset($result[ $match[1] ]))
			{
				$result[ $match[1] ] = array();
			}

			if ($primary_key!==null and !empty($item['parameters'][$primary_key]))
			{
				$result[ $match[1] ][ $item['parameters'][ $primary_key ] ] = $item;
			}
			else
			{
				array_push($result[ $match[1] ], $item);
			}
		}

		return $return_array
			? $result
			: array_shift($result);
	}

	private static function ParseParameters($string)
	{
		$parameters = array();

		$string = trim($string);
		if (empty($string))
		{
			return $parameters;
		}

		preg_match_all('/([^=\s]+\s*)(?:=\s*"([^"]*?)")?(?:\s|$)?/imsS', $string, $matches, PREG_SET_ORDER);

		foreach ($matches as $match)
		{
			$parameters[trim($match[1])] = isset($match[2])
				? $match[2]
				: null;
		}

		return $parameters;

	}
}
?>