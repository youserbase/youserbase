<?php
class Session
{
	private static $initialized = false;
	private static $data = array();

	public static function Set()
	{
		self::init();

		$arguments = func_get_args();

		$node = &$_SESSION;
		while (count($arguments)>1)
		{
			$key = array_shift($arguments);
			if (!isset($node[$key]))
			{
				$node[$key] = null;
			}
			elseif (!is_array($node[$key]))
			{
				$node[$key] = array($node[$key]);
			}
			$node = &$node[$key];
		}
		$node = array_shift($arguments);
	}

	public static function Stuff()
	{
		self::init();

		$arguments = func_get_args();

		// TODO: Fehlermeldung
		if (count($arguments)<2)
		{
			return;
		}

		$node = &$_SESSION;
		while (count($arguments)>1)
		{
			$key = array_shift($arguments);
			if (!isset($node[$key]))
			{
				$node[$key] = null;
			}
			elseif (!is_array($node[$key]))
			{
				$node[$key] = array($node[$key]);
			}
			$node = &$node[$key];
		}
		if (is_array($node))
		{
			array_push($node, array_shift($arguments));
		}
		else
		{
			$node = array_shift($arguments);
		}
	}

	public static function Get()
	{
		self::init();

		$arguments = func_get_args();

		$value = $_SESSION;
		foreach ($arguments as $argument)
		{
			if (!is_array($value) or !isset($value[$argument]))
			{
				$value = null;
				break;
			}
			$value = $value[$argument];
		}
		return $value;
	}

	public static function Clear()
	{
		self::init();

		$arguments = func_get_args();
		$node = &$_SESSION;
		while (count($arguments)>1)
		{
			if (!isset($node[reset($arguments)]))
			{
				return false;
			}
			$node = &$node[array_shift($arguments)];
		}
		unset($node[array_shift($arguments)]);
		return true;
	}

 	public static function GetAndClear()
 	{
 		$arguments = func_get_args();
 		$value = call_user_func_array(array('self','Get'), $arguments);
 		call_user_func_array(array('self', 'Clear'), $arguments);

 		return $value;
 	}

	private static function init()
	{
		if (!self::$initialized)
		{
			session_start();
			self::$initialized = true;
		}
	}
}
?>