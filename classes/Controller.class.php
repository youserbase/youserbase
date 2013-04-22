<?php
/**
 * @package youserbase
 * @subpackage core
 */

abstract class Controller
{
	protected static $controllers = null;

	protected $module;
	protected $method;
	protected $callable_methods;
	protected $parameters;

	protected $template = false;

	protected $via_ajax = false;

	public function __construct($module, $method, $parameters=array())
	{
		$this->module = $module;
		$this->method = $method;
		$this->callable_methods = array_diff(get_class_methods($this), get_class_methods(__CLASS__));
		$this->parameters = $parameters;
		$this->get_template(true);

		$this->via_ajax = $GLOBALS['VIA_AJAX'];
	}

	public static function GetAvailableActions($asString=false, $hierarchic=false)
	{
		$dbt = debug_backtrace();
		$filename = str_replace(APP_DIR, '.', $dbt[0]['file']);
		$line = $dbt[0]['line'];
		Dobber::ReportWarning('Use of deprecated function %s in %s:%d', __METHOD__, $filename, $line);

		return FrontController::GetAvailableActions($asString, $hierarchic);
	}

	public static function SetControllers($controllers)
	{
		self::$controllers = $controllers;
	}

	public static function GetControllers()
	{
		if (self::$controllers === null)
		{
			$controllers = glob('./controllers/*/*.class.php', GLOB_NOSORT);

			self::$controllers = array();
			foreach ($controllers as $controller)
			{
				$class = basename($controller, '.class.php');
				$module = ucfirst(basename(dirname($controller)));
				if (!isset(self::$controllers[$module]))
					self::$controllers[$module] = array();
				array_push(self::$controllers[$module], $class);
			}
		}
		return self::$controllers;
	}

	public static function getModule()
	{
		echo __FILE__."\n";
		print_r(debug_backtrace());
		die;
	}

	public function valid()
	{
		return in_array($this->method, $this->callable_methods);
	}

	public function execute()
	{
		Event::Dispatch('alert', 'Control:Execute', $this->module.'/'.str_replace($this->module.'_', '', get_class($this)).'/'.$this->method);

		$method = $this->method;
		if ($this->via_ajax and in_array($method.'_AJAX', $this->callable_methods))
			$method .= '_AJAX';
		elseif ($this->Posted() and in_array($method.'_POST', $this->callable_methods))
			$method .= '_POST';

		if (is_callable(array($this, '__prepare')))
			call_user_func(array($this, '__prepare'));

		if (call_user_func_array(array($this, $method), $this->parameters)===false)
		{
			$this->get_template(false, 'No content');
			if ($method != $this->method)
				call_user_func_array(array($this, $this->method), $this->parameters);
		}

		return $this;
	}

	public function Posted()
	{
		$arguments = func_get_args();
		if (empty($arguments))
			return !empty($_POST);

		foreach ($arguments as $argument)
			if (!isset($_POST[$argument]))
				return false;
		return true;
	}

	// Ugly as hell, i kow
	public function Getted()
	{
		$arguments = func_get_args();
		if (empty($arguments))
			return !empty($_GET);

		foreach ($arguments as $argument)
			if (empty($_GET[$argument]))
				return false;
		return true;
	}

	public function JSON($data)
	{
		Header('X-JSON: '.FastJSON::encode($data));
	}

	public function &get_template($template_file=null, $postfix=null)
	{
		if (is_object($template_file) and is_a($template_file, 'Template'))
			$this->template = $template_file;
		elseif ($template_file===null)
		{
			if ($this->template === false)
				$this->template = new Template(false, $postfix);
		}
		elseif ($template_file===true)
		{
			$template_file = $this->searchTemplateFile($this->method, $postfix);
			$this->template = new Template($template_file);
		}
		else
		{
			$template_file =  $this->searchTemplateFile($template_file, $postfix);
			$this->template = new Template($template_file, $postfix);
		}

		$this->template->assign('VIA_AJAX', $this->via_ajax);
		return $this->template;
	}

	public function set_template(&$template)
	{
		$this->template = $template;
	}

	public function &create_template($filename, $postfix=null)
	{
		$template = new Template($this->searchTemplateFile($filename, $postfix));
		return $template;
	}

	public function &getTemplate($template_file=null, $postfix=null)
	{
		$dbt = debug_backtrace();
		$filename = str_replace(APP_DIR, '.', $dbt[0]['file']);
		$line = $dbt[0]['line'];
		Dobber::ReportWarning('Use of deprecated function %s in %s:%d', __METHOD__, $filename, $line);
		return $this->get_template($template_file, $postfix);
	}

	private function searchTemplateFile($filename, $postfix)
	{
		$template_dir_template = './controllers/%s/templates/%s_%s%s';

		if ($postfix!==null)
			$postfix = '_'.$postfix;

		$template_file = sprintf($template_dir_template, $this->module, str_replace(ucfirst($this->module).'_', '', get_class($this)), $filename, $postfix);
		if (!count(glob(strtolower($template_file).'.*', GLOB_NOSORT)))
			$template_file = sprintf($template_dir_template, $this->module, get_class($this), $this->method, $postfix);
		if ($this->via_ajax and count(glob(strtolower($template_file).'.ajax.*', GLOB_NOSORT)))
			$template_file .= '.ajax';
		return count(glob(strtolower($template_file).'.*', GLOB_NOSORT))
			? strtolower($template_file)
			: $filename;
	}

	public static function RenderAndDisplay()
	{
		$arguments = func_get_args();

		call_user_func_array(array('FrontController', 'FakeLocation'), $arguments);

		$controller = call_user_func_array(array('FrontController', 'Get'), $arguments);
		$result = $controller->execute()->get_template()->render();

		FrontController::FakeLocation();

		return $result;
	}

	public static function Render($module, $controller, $method)
	{
		$arguments = func_get_args();
		$arguments = array_slice($arguments, 3);

		if (!method_exists($controller, $method))
			return 'Invalid call';

		FrontController::FakeLocation($module, $controller, $method);

		$controller = new $controller($module, $method);
		call_user_func_array(array($controller, $method), $arguments);
		$result = $controller->get_template()->render();

		FrontController::FakeLocation();

		return $result;
	}

	public function get_navigation()
	{
		return isset($this->navigation)
			? $this->navigation
			: false;
	}
}
?>