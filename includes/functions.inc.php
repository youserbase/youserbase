<?php
function pick($array, $index)
{
	if (count($array)<$index)
	{
		return false;
	}
	$temp = array_slice($array, $index, 1);
	return reset($temp);
}

function array_collect($callback, $array)
{
	$arguments = func_get_args();
	$arguments = array_slice($arguments, 2);

	$result = array();
	foreach ($array as $key=>$value)
	{
		if (call_user_func_array($callback, array_merge($arguments, array($value))))
		{
			$result[$key] = $value;
		}
	}
	return $result;
}

function array_random($array)
{
	return $array[array_rand($array)];
}

function array_reject($callback, $array)
{
	$arguments = func_get_args();

	$result = call_user_func_array('array_collect', $arguments);
	return array_diff($array, $result);
}

function array_key_sum($array, $key, $initial=0)
{
	foreach ($array as $item)
	{
		$initial += $item[$key];
	}
	return $initial;
}

function phrase_parameters($array)
{
	$params = array();
	foreach ((array)$array as $key=>$value)
	{
		if (is_string($value) or is_bool($value) or is_numeric($value))
		{
			array_push($params, sprintf('%s="%s"', $key, strip_tags($value)));
		}
	}
	return implode(' ', $params);
}

function array_map_recursive($callback, $array)
{
	foreach ($array as &$item)
	{
		if (is_array($item))
		{
			$item = array_map_recursive($callback, $item);
		}
		else
		{
			$item = call_user_func($callback, $item);
		}
	}
	return $array;
}

function array_to_table($array)
{
	$result = '<table cellspacing="0" cellpadding="2" style="width: 100%; border-collapse: collapse;">';

	$keys = array_keys(reset($array));

	$result .= '<thead><tr>';
	foreach ($keys as $key)
	{
		$result .= '<th>'.htmlentities($key).'</th>';
	}
	$result .= '</tr></thead>';

	$result .= '<tbody>';
	foreach ($array as $row)
	{
		$result .= '<tr style="text-align: center;">';
		foreach ($row as $value)
		{
			if ($value===null)
			{
				$value = 'NULL';
			}

			$result .= '<td style="border: 1px solid #777;">'.htmlentities($value).'</td>';
		}
		$result .= '</tr>';
	}
	$result .= '</tbody>';

	$result .= '</table>';

	return $result;
}

function if_empty($value, $default=null)
{
	return empty($value)
		? $default
		: $value;
}

function as_boolean($var)
{
	return is_string($var)
		? in_array(strtolower(trim($var)), array('1', 'true', 'on', 'yes'), true)
		: (boolean)$var;
}

function string_wrap($string, $limit, $append='&hellip;')
{
	$separator = '|'.md5(uniqid('dummy', true)).'|';

	$temp = wordwrap($string, $limit, $separator, false);

	$array = array_map('trim', explode($separator, $temp));
	$temp = $array[0];

	if (strlen($temp)<strlen($string) and !in_array(substr($temp, -1, 1), array('.', '?'. '!')))
	{
		$temp .= $append;
	}

	return $temp;
}

function strip_non_digits($string, $complete = false)
{
	return $complete
		? preg_replace('/[^[:digit:]]/', '', trim($string))
		: preg_replace('/[^[:digit:]\.]/', '', str_replace(',', '.', trim($string)));
}

function numberformat($number, $decimals=0, $decimal_separator = null, $thousands_separator = null)
{
	return number_format(
		$number,
		$decimals,
		$decimal_separator===null ? Locale::Get('decimal_separator') : $decimal_separator,
		$thousands_separator===null ? Locale::Get('thousands_separator') : $thousands_separator
	);
}

function dateformat($timestamp=null, $format=null, $quiet=false)
{
	switch ($format)
	{
		case 'date':
			$format = Locale::Get('date_format');
		break;
		case 'time':
			$format = Locale::Get('time_format');
		break;
		case null:
			$format = Locale::Get('datetime_format');
		break;
		default:
		break;
	}
	$result = date($format, $timestamp===null ? time() : $timestamp);
	if (!$quiet)
	{
		$result = sprintf('<span rel="%u">%s</span>',
			$timestamp,
			$result
		);
	}

	return $result;
}

function twittertime($timestamp)
{
	$delta = time() - $timestamp;
	if ($delta < 60) {
		return '<phrase id="TIME_LESS_THAN_A_MINUTE_AGO"/>';
	} else if ($delta < 120) {
		return '<phrase id="TIME_ABOUT_A_MINUTE_AGO"/>';
	} else if ($delta < (45 * 60)) {
		return '<phrase id="TIME_MINUTES_AGO" minutes="'. floor($delta / 60).'"/>';
	} else if ($delta < (90 * 60)) {
		return '<phrase id="TIME_ABOUT_AN_HOUR_AGO"/>';
	} else if ($delta < (24 * 60 * 60)) {
		return ' <phrase id="TIME_ABOUT_HOURS_AGO" hours="'.floor($delta / 3600).'"/>';
	} else if ($delta < (48 * 60 * 60)) {
		return '<phrase id="TIME_A_DAY_AGO"/>';
	} else {
		return ' <phrase id="TIME_DAYS_AGO" days="'.floor($delta / 86400).'"/>';
	}
}

function map_language($language)
{
	$language_mapping = array(
		'sv'=>'se',
		'cs'=>'cz',
	);

	return isset($language_mapping[$language])
		? $language_mapping[$language]
		: $language;
}

function sprintf_ready($string, $characters=array('(',')'))
{
	return preg_replace('/('.implode('|', array_map('urlencode', $characters)).')/', '%$1', $string);
}

function str_truncate($text, $limit, $uniqSeparator = '%%~`~%%')
{
	return strlen($text) <= $limit
		? $text
		: current(explode($uniqSeparator, wordwrap($text, $limit, $uniqSeparator), 2)).'&hellip;';
}
?>