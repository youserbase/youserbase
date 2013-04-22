<?php
class Token
{
	protected static $instance = null;

	public function __construct()
	{
		if (empty($_SESSION['TOKEN']))
		{
			$_SESSION['TOKEN'] = array(
				'secret'=>md5(uniqid('secret', true)),
				'invalid_tokens'=>array()
			);
		}
	}

	public static function &getInstance()
	{
		if (self::$instance===null)
		{
			self::$instance = new Token();
		}

		foreach ($_SESSION['TOKEN']['invalid_tokens'] as $token=>$timestamp)
		{
			if ($timestamp<time())
			{
				unset($_SESSION['TOKEN']['invalid_tokens'][$token]);
			}
		}

		return self::$instance;
	}

	public function generate()
	{
		$timestamp = time();
		$arguments = func_get_args();

		$token = md5(sprintf('%s|%s|%d', $_SESSION['TOKEN']['secret'], implode('|', $arguments), $timestamp));

		return $token.':'.dechex($timestamp);
	}

	public function validate()
	{
		$arguments = func_get_args();
		list($token_to_validate, $timestamp) = explode(':', array_shift($arguments));

		if (in_array($token_to_validate, @array_keys(@$_SESSION['TOKEN']['invalid_tokens'])) or hexdec($timestamp)<time()-TOKEN_LIFETIME)
		{
			return false;
		}
		$this->invalidate($token_to_test, hexdec($timestamp));

		$token = md5(sprintf('%s|%s|%d', $_SESSION['TOKEN']['secret'], implode('|', $arguments), hexdec($timestamp)));

		return $token_to_test==$token;
	}

	public function invalidate($token, $timestamp)
	{
		$_SESSION['invalid_tokens'][$token] = $timestamp;
	}
}
?>