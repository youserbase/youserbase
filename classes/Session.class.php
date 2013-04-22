<?php
/**
 * Session.class.php
 *
 * Provides session features independent of PHP's builtin session management.
 *
 * @author     Jan-Hendrik Willms <tleilax@gmail.com>
 * @uses       Cookie stores session key in cookie
 * @uses       DBManager stores session data in database
 * @version    0.9
 * @copyright  youserbase 2008
 * @package    youserbase
 * @subpackage core
 *
 */
class Session
{
	protected $id;
	protected $data;
	public $lifetime = null;

	private function __construct()
	{
		if (!Cookie::Get('SESSION_ID'))
		{
			$this->id = md5(uniqid('session', true));
			$this->data = array();

			Cookie::Set('SESSION_ID', $this->id);
		}
		else
		{
			$this->id = Cookie::Get('SESSION_ID');
			$session_data = DBManager::Get()->query("SELECT data, UNIX_TIMESTAMP(expires) AS expires FROM sessions WHERE session_id=?", $this->id)->fetch_item(array());
			$this->data = unserialize($session_data['data']);
			$this->lifetime = $session_data['expires'];
		}
		register_shutdown_function(array($this, 'destruct'));
	}

	public function destruct()
	{
		$time_part = 'FROM_UNIXTIME(?)';
		if ($this->lifetime!==null and $this->lifetime{0}=='+')
		{
			$this->lifetime = (int)substr($this->lifetime, 1);
			$time_part = 'TIMESTAMPADD(SECOND, ?, NOW())';
		}
		elseif (empty($this->lifetime))
		{
			$this->lifetime = null;
		}

		DBManager::Get()->query("INSERT INTO sessions (session_id, data, expires, last_update) VALUES (?, ?, ".$time_part.", NOW()) ON DUPLICATE KEY UPDATE data=VALUES(data), expires=VALUES(expires), last_update=VALUES(last_update)",
			$this->id,
			serialize($this->data),
			$this->lifetime
		);
	}

	public function get_value($path)
	{
		$value = $this->traverse($path, false);
		return $value!==false
			? $value
			: null;
	}

	public function set_value($path, $value)
	{
		$node =& $this->traverse($path);
		$node = $value;
	}

	public function stuff_value($path, $value)
	{
		$node =& $this->traverse($path);

		if (empty($node))
		{
			$node = $value;
		}
		elseif (!is_array($node))
		{
			$node = array($node, $value);
		}
		else
		{
			array_push($node, $value);
		}
	}

	public function clear_value($path)
	{
		$key = array_pop($path);

		$node =& $this->traverse($path, false);
		if (!$node or !isset($node[$key]))
		{
			return false;
		}

		unset($node[$key]);

		return true;
	}

	private function &traverse($path, $extend = true)
	{
		$node =& $this->data;
		foreach (array_filter($path) as $key)
		{
			if (!$extend and (!is_array($node) or !isset($node[$key])))
			{
				$temp = false;
				return $temp;
			}
			elseif ($extend and !isset($node[$key]))
			{
				$node[$key] = array();
			}
			elseif ($extend and !is_array($node[$key]))
			{
				$node[$key] = array($node[$key]);
			}
			$node =& $node[$key];
		}
		return $node;
	}

	public function get_id()
	{
		return $this->id;
	}
/*
 * Static
 */
	private static $instance = null;

	public static function &GetInstance()
	{
		if (self::$instance===null)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function Id()
	{
		$instance = self::GetInstance();
		return $instance->get_id();
	}

	public static function Get()
	{
		$instance = self::GetInstance();
		$arguments = func_get_args();

		return call_user_func(array(&$instance, 'get_value'), $arguments);
	}

	public static function Set()
	{
		$instance = self::GetInstance();
		$arguments = func_get_args();
		$value = array_pop($arguments);

		call_user_func(array(&$instance, 'set_value'), $arguments, $value);
	}

	public static function Stuff()
	{
		$instance = self::GetInstance();
		$arguments = func_get_args();
		$value = array_pop($arguments);

		call_user_func(array(&$instance, 'stuff_value'), $arguments, $value);
	}

	public static function Clear()
	{
		$instance = self::GetInstance();
		$arguments = func_get_args();

		call_user_func(array(&$instance, 'clear_value'), $arguments);
	}

	public static function GetAndClear()
	{
		$arguments = func_get_args();
		$value = call_user_func_array(array('self', 'Get'), $arguments);
		call_user_func_array(array('self', 'Clear'), $arguments);

		return $value;
	}

	public static function Prolong($expires)
	{
		Cookie::Set('SESSION_ID', Cookie::Get('SESSION_ID'), $expires);
		self::GetInstance()->lifetime = $expires;
	}

	public static function CollectGarbage()
	{
		DBManager::Get()->query("DELETE QUICK FROM sessions WHERE (expires IS NULL OR expires<NOW()) AND last_update<TIMESTAMPADD(MINUTE, ?, NOW())",
			ini_get('session.gc_maxlifetime')
		);
		DBManager::Get()->query("OPTIMIZE TABLE sessions");
	}
}
?>