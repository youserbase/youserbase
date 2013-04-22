<?php
class Parameters
{
	private $parameters;

	public function __construct($parameters)
	{
		$this->parameters = is_array($parameters)
			? $parameters
			: self::Extract($parameters);
	}

	public function is_set($key)
	{
		return isset($this->parameters[$key]);
	}

	public function is_empty($key)
	{
		return empty($this->parameters[$key]);
	}

	public function get($key = null)
	{
		if ($key === null)
		{
			return $this->parameters;
		}

		return $this->is_set($key)
			? $this->parameters[$key]
			: null;
	}

	public function get_boolean($key, $default)
	{
		return $this->is_set($key)
			? !in_array(trim($this->get($key)), array(0, false, '', 'false', 'off', 'no'), true)
			: $default;
	}

	private static function Extract($string)
	{
		$parameters = array();

		$string = trim($string);
		if (empty($string))
		{
			return $parameters;
		}

		$matched = preg_match_all('/(\S+)="([^"]*?)"/imsS', $string, $temp_parameters);
		for ($i=0; $i<$matched; $i++)
		{
			$parameters[trim($temp_parameters[1][$i])] = $temp_parameters[2][$i];
		}

		return $parameters;
	}

}
?>