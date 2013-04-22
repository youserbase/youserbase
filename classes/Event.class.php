<?php
/**
 * Event handler
 *
 * @author Jan-Hendrik Willms <tleilax@mindfuck.de>
 * @version 1.1.0
 *
 */
class Event
{
	protected static $instance = null;
	protected static $hooks = array(), $files = array();

	protected $methods = array(
		'ALERT'=>array(
			'initialize'=>'return array_pop($arguments);',
			'callback'=>'$result = $value;',
			'finalize'=>'return $result;'
		),
		'HARVEST'=>array(
			'initialize'=>'return array();',
			'callback'=>'$result = array_merge($result, array($value));',
			'finalize'=>'return array_filter($result);'
		),
		'SHAPE'=>array(
			'initialize'=>'return array_pop($arguments);',
			'callback'=>'$result = $value;',
			'finalize'=>'return $result;'
		),
		'GRAB'=>array(
			'initialize'=>'return false;',
			'callback'=>'if ($value!==false) { $result=$value; $cancel = true; };',
			'finalize'=>'return $result;'
		)
	);

	/**
	 * Constructor, initialises methods array and registers system wide hooks
	 * @access public
	 */
	public function __construct()
	{
		foreach ($this->methods as $name=>$method)
		{
			$this->methods[$name]['initialize'] = create_function('$arguments', $method['initialize']);
			$this->methods[$name]['callback'] = create_function('&$result, $value, &$cancel', $method['callback']);
			$this->methods[$name]['finalize'] = create_function('$result', $method['finalize']);
		}
	}

	public function trigger($event, $type, &$arguments)
	{
		if (!isset(self::$hooks[$event]))
			return false;
		$hooks = self::$hooks[$event];

		if (!isset($this->methods[strtoupper($type)]))
			throw new EventException(sprintf('Unknown method `%s` called', $type));
		$methods = $this->methods[strtoupper($type)];

		$result = $methods['initialize']($arguments);

		$cancel = false;
		foreach ($hooks as $hook_class=>$hook_method)
		{
			foreach ((array)$hook_method as $method)
			{
				try {
					$temp_result = call_user_func_array(array($hook_class, $method), &$arguments);

					call_user_func_array($methods['callback'], array(&$result, $temp_result, $cancel));
					// A bit ugly
					if (strtoupper($type)=='SHAPE')
						array_splice($arguments, 0, 1, array($temp_result));
				} catch (EventException $e)	{
				}

				if ($cancel)
					break 2;
			}
		}

		$result = $methods['finalize']($result);

		return $result;
	}

	public static function Dispatch($type, $event)
	{
		$arguments = array_slice(func_get_args(), 2);

		if (self::$instance===null)
			self::$instance = new self();

		if (empty(self::$hooks[$event]))
			return self::$instance->methods[strtoupper($type)]['initialize']($arguments);

		Timer::Report('Before "%s"', $event);

		$result = self::$instance->trigger($event.':Before', $type, &$arguments);
		Timer::Report('Between "%s"', $event);
		$result = self::$instance->trigger($event, $type, &$arguments);
		Timer::Report('After "%s"', $event);
		if ($after_result = self::$instance->trigger($event.':After', $type, &$arguments))
		{
			$result = $after_result;
		}
		Timer::Report('Finished "%s"', $event);


		return $result;
	}

	public static function GetFiles()
	{
		return self::$files;
	}

	public static function SetHooks($hooks)
	{
		self::$hooks = $hooks;
	}

	public static function GetHooks($name=null, $as_string = false)
	{
		if ($name===null)
			return self::$hooks;

		$result = array();

		if (!isset(self::$hooks[$name]))
			return $result;

		foreach (self::$hooks[$name] as $hook_class=>$hook_method)
			array_push($result, $as_string
				? $hook_class.':'.$hook_method
				: array(
					'class'=>$hook_class,
					'method'=>$hook_method
				)
			);
		return $result;
	}

	public static function RegisterHooks($path)
	{
		$arguments = func_get_args();
		foreach ($arguments as $path)
		{
//			array_map(array('self', __METHOD__), glob($path.'/*', GLOB_ONLYDIR));

			$files = glob($path.'/*.'.sql_regcase('class.php'), GLOB_NOSORT);
			foreach ($files as $file)
			{
				require $file;
				$hook = basename($file, '.class.php');

				if (!is_subclass_of($hook, 'Hook'))
					continue;

				self::$files[strtolower($hook)] = realpath($file);

				$vars = get_class_vars($hook);
				if (!isset($vars['hooks']))
					continue;
				$hooks = $vars['hooks'];

				foreach ($hooks as $name=>$method)
				{
					if (!isset(self::$hooks[$name]))
						self::$hooks[$name] = array();
					if (!isset(self::$hooks[$name][$hook]))
						self::$hooks[$name][$hook] = array();
					foreach ((array)$method as $m)
						array_push(self::$hooks[$name][$hook], $m);
				}
			}
		}
		ksort(self::$hooks);
	}
}
?>