<?php
/**
 * Possible todos:
 *
 * - Find the perfect moment for the cookies to be sent
 *
 */
class Cookie
{
	private static $set = array();

	public static function Get($name)
	{
		return isset(self::$set[$name])
			? self::$set[$name][0]
			: (isset($_COOKIE[$name])
				? $_COOKIE[$name]
				: false);
	}

	public static function Set($name, $value, $expires=null, $path=false, $domain=false, $secure=null)
	{
		if ($expires!==null and $expires[0]=='+')
		{
			$expires = time()+(int)substr($expires, 1);
		}
		elseif ($expires!==null and $expires[0]=='-')
		{
			$expires = time()-(int)substr($expires, 1);
		}

		if ($path===false)
		{
			$path = dirname($_SERVER['PHP_SELF']);
		}
		if ($domain===false)
		{
			$domain = $_SERVER['SERVER_NAME'];
		}
		setcookie($name, $value, $expires, $path, $domain, $secure);

		if (empty($value) and isset(self::$set[$name]))
		{
			unset(self::$set[$name]);
		}
		elseif (!empty($value))
		{
			self::$set[$name] = array(
				$value,
				$expires,
				$path,
				$domain,
				$secure
			);
		}
	}

	public static function Clear($name, $value='', $expires=false, $path=false, $domain=false, $secure=null)
	{
		if ($expires==false)
		{
			$expires = strtotime('-1 year');
		}
		self::Set($name, $value, $expires, $path, $domain, $secure);
	}
}
?>