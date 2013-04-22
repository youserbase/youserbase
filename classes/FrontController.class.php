<?php
/**
 * FrontController class
 *
 */
class FrontController
{
	private static $faked_location = null;
	private static $original_location = null;
	public static function FakeLocation()
	{
		$arguments = func_get_args();
		self::$faked_location = count($arguments)
			? call_user_func_array(array('self', 'GetLocation'), $arguments)
			: null;
	}

	public static function GetAvailableActions($asString = false, $hierarchic = false)
	{
		$actions = array();

		Controller::GetControllers();

		$controller_methods = get_class_methods('Controller');

		$modules = glob('./controllers/*', GLOB_ONLYDIR + GLOB_NOSORT);
		foreach ($modules as $module)
		{
			$controllers = glob($module.'/*.class.php', GLOB_NOSORT);
			foreach ($controllers as $controller)
			{
				$controller_name = basename($controller, '.class.php');

				$class_methods = get_class_methods($controller_name);
				$class_methods = array_diff($class_methods, $controller_methods);

				sort($class_methods);

				foreach ($class_methods as $method)
				{
					if (preg_match('/_(AJAX|POST)$/S', $method) and in_array(preg_replace('/_(AJAX|POST)$/S', '', $method), $class_methods))
						continue;
					if ($method=='__prepare')
						continue;
					$action = array(
						'module'=>ucfirst(basename($module)),
						'controller'=>str_replace(ucfirst(basename($module)).'_', '', $controller_name),
						'method'=>$method
					);

					$index = strtoupper(md5(implode('|', $action)));

					if ($hierarchic)
					{
						if (!isset($actions[$action['module']]))
							$actions[$action['module']] = array();
						if (!isset($actions[$action['module']][$action['controller']]))
							$actions[$action['module']][$action['controller']] = array();
						$actions[$action['module']][$action['controller']][$index] = $action['method'];
					}
					else
						$actions[$index] = $asString
							? implode(is_string($asString)?$asString:'/', $action)
							: $action;
				}
			}
		}

		return $actions;
	}


	public static function Get()
	{
		$arguments = func_get_args();
		$parameters = is_array(end($arguments)) ? array_pop($arguments) : array();
		$location = call_user_func_array(array('self', 'GetLocation'), $arguments);

		$location = Event::Dispatch('shape', 'Controller:Got', $location);

		if (self::CheckLocation($location)===false)
			throw new FrontControllerException(sprintf('Unknown method "%s" called', implode(',', $location)), 0, $location);

		$controller = new $location['class']($location['module'], $location['method'], $parameters);

		return $controller;
	}

	public static function GetURL($language = null)
	{
		if ($language === null)
			$language = BabelFish::GetLanguage();

		$uri = (strlen(dirname($_SERVER['REQUEST_URI'])) == 1)
			? str_replace('/'.BabelFish::GetLanguage().'/', '/'.$language.'/', $_SERVER['REQUEST_URI'])
			: str_replace(dirname($_SERVER['PHP_SELF']).'/'.BabelFish::GetLanguage().'/', dirname($_SERVER['PHP_SELF']).'/'.$language.'/', $_SERVER['REQUEST_URI']);

		if ($uri==$_SERVER['REQUEST_URI'] and strpos($uri, '/'.$language.'/')===false)
			$uri = preg_replace('/^((?:'.preg_quote(dirname($_SERVER['PHP_SELF']), '/').')?\/)('.BabelFish::GetLanguage().'\/)?/', '$1'.$language.'/', $_SERVER['REQUEST_URI']);
		$uri = rtrim(preg_replace('/([&?])return_to=[^&]+(&|$)/', '$1', $uri), '&?');

		return self::GetHost().$uri;
	}

	public static function GetOriginalLink()
	{
		return self::GetLink(self::$original_location['module'], self::$original_location['controller'], self::$original_location['method']);
	}

	public static function GetLink()
	{
		$arguments = func_get_args();
		if (count($arguments)==1 and $arguments[0]===false)
			return '';

		$parameters = array(
			'preferred_language'=>BabelFish::GetLanguage()
		);
		if (is_array(end($arguments)))
			$parameters = array_merge($parameters, array_pop($arguments));
		if (empty($arguments))
		{
			parse_str($_SERVER['QUERY_STRING'], $url_parameters);
			unset($url_parameters['module'], $url_parameters['controller'], $url_parameters['method'], $url_parameters['preferred_language']);
			$parameters = array_merge($url_parameters, $parameters);
		}
		$location = self::GetLocation();

		if (count($arguments)==1 and strpos($arguments[0], '/')!==false)
		{
			list($preferred_language, $module, $controller, $method) = array_slice(explode('/', '///'.$arguments[0]), -4);
			if (!empty($method))
				$location['method'] = $method;
			if (!empty($controller))
				$location['controller'] = $controller;
			if (!empty($module))
				$location['module'] = $module;
			if (!empty($preferred_language))
				$parameters['preferred_language'] = $preferred_language;
			$arguments = array();
		}

		switch (count($arguments))
		{
			case 1:
				$location['method'] = $arguments[0];
			break;
			case 2:
				$location['controller'] = $arguments[0];
				$location['method'] = $arguments[1];
			break;
			case 3:
				$location['module'] = $arguments[0];
				$location['controller'] = $arguments[1];
				$location['method'] = $arguments[2];
			break;
			default:
			break;
		}

		$location['controller'] = str_ireplace($location['module'].'_', '', $location['controller']);
		$data = array(
			'location'=>$location,
			'parameters'=>$parameters,
			'param_separator'=>'&amp;'
		);


		$data['param_separator'] = '?';

		if (false === ($data['link'] = Dispatcher::Map($data)))
		{
			$data['link'] = $data['location']['module'];
			if (!empty($data['location']['controller']))
				$data['link'] .= '/'.$data['location']['controller'];
			if (!empty($data['location']['method']))
				$data['link'] .= '/'.$data['location']['method'];
		}

		if (isset($data['parameters']['preferred_language']) and $data['parameters']['preferred_language']!==false)
		{
			$data['link'] = $data['parameters']['preferred_language'].'/'.$data['link'];
			unset($data['parameters']['preferred_language']);
		}

		if (empty($data['link']))
			$data['link'] = sprintf('%s?module=%s&amp;controller=%s&amp;method=%s',
				basename($_SERVER['PHP_SELF']),
				$data['location']['module'],
				$data['location']['controller'],
				$data['location']['method']
			);

		$hash = null;
		if (!empty($parameters['#']))
		{
			$hash = $data['parameters']['#'];
			unset($data['parameters']['#']);
		}

		$params = array();
		foreach ($data['parameters'] as $key=>$value)
		{
			if (false === $value)
				continue;
			if (is_array($value))
				foreach ($value as $k=>$v)
				{
					$param = $key.'['.urlencode($k).']';
					if ($v !== null)
						$param .= '='.(preg_match('/^#\{\w+\}$/', $v) ? $v : urlencode($v));
					array_push($params, $param);
				}
			else
			{
				$param = $key;
				if ($value!==null)
					$param .= '='.(preg_match('/^#\{\w+\}$/', $value) ? $value : urlencode($value));
				array_push($params, $param);
			}
		}

		if (!empty($params))
			$data['link'] .= $data['param_separator'].implode('&amp;', $params);
		if (!empty($hash))
			$data['link'] .= '#'.$hash;

		return $data['link'];
	}

	private static function AdjustLocation($key, &$arguments, &$location)
	{
		if (!empty($arguments) and ($value = array_pop($arguments)))
			$location[$key] = urldecode($value);
		elseif (!self::$faked_location and isset($_REQUEST[$key]))
			$location[$key] = urldecode($_REQUEST[$key]);
	}

	private static $locations = array();

	public static function GetLocation()
	{
		$arguments = func_get_args();
		$location = self::$faked_location
			? self::$faked_location
			: array(
				'module' => 'System',
				'controller' => 'System',
				'method' => 'Index',
			);

		self::AdjustLocation('method', $arguments, $location);
		self::AdjustLocation('controller', $arguments, $location);
		self::AdjustLocation('module', $arguments, $location);

		if (self::$original_location === null)
			self::$original_location = $location;

		$location['controller'] = str_replace($location['module'].'_', '', $location['controller']);

		$hash = md5(implode($location));
		if (empty(self::$locations[ $hash ]))
//			self::$locations[ $hash ] = Event::Dispatch('shape', 'FrontController:GetAlias', $location);
			self::$locations[ $hash ] = $location;
		$location = self::$locations[ $hash ];

		unset($location['class']);
		return $location;
	}

	public static function GetLocationHash()
	{
		$arguments = func_get_args();
		$location = call_user_func_array(array('self', 'GetLocation'), $arguments);

		return strtoupper(md5(implode('|', $location)));
	}

	public static function IsLocation($module, $controller=null, $method=null)
	{
		$location = self::GetLocation();

		if (is_array($module))
		{
			$method = array_pop($module);
			$controller = array_pop($module);
			$module = array_pop($module);
		}

		$valid = true;
		$valid = ($valid and ($module == $location['module']));
		$valid = ($valid and (($controller === null) or ($controller == $location['controller'])));
		$valid = ($valid and (($method === null) or ($method == $location['method'])));

		return $valid;
	}

	public static function CheckLocation(&$location, $location_string = null)
	{
		if (!is_array($location))
			$location = array();
		if ($location_string!==null)
		{
			$location_temp = explode('/', $location_string);
			if ($method=array_pop($location_temp))
				$location['method'] = $method;

			if ($controller=array_pop($location_temp))
				$location['controller'] = $controller;

			if ($module=array_pop($location_temp))
				$location['module'] = $module;

			unset($location['class']);
		}
		if (!isset($location['module'], $location['controller'], $location['method']))
			return false;

		if (empty($location['class']) or !class_exists($location['class']) or !is_subclass_of($location['class'], 'Controller'))
			$location['class'] = ucfirst($location['module']).'_'.$location['controller'];

		if (!class_exists($location['class']) or !is_subclass_of($location['class'], 'Controller'))
			$location['class'] = $location['controller'];

		if (!class_exists($location['class']) or !is_subclass_of($location['class'], 'Controller'))
			return false;

		return method_exists($location['class'], $location['method']);
	}

	public static function GetBreadCrumbs()
	{
		$location_hash = self::GetLocationHash();
		$parent_sites = Navigation::GetParentSites();
		$sites = self::GetAvailableActions();

		$bread_crumbs = array(
			$location_hash=>$sites[$location_hash]
		);

		while (isset($parent_sites[$location_hash]))
		{
			$bread_crumbs[$parent_sites[$location_hash]] = $sites[$parent_sites[$location_hash]];
			$location_hash = $parent_sites[$location_hash];
		}
		return array_reverse($bread_crumbs, true);
	}

	public static function Relocate()
	{
		$arguments = func_get_args();
		if ($GLOBALS['VIA_AJAX'])
		{
			if (is_array(end($arguments)))
				$arguments[count($arguments)-1]['REMOVE_LAYOUT'] = null;
			else
				array_push($arguments, array('REMOVE_LAYOUT'=>null));
		}
		self::DirectRelocate(call_user_func_array(array('self', 'GetLink'), $arguments));
	}

	public static function DirectRelocate($location)
	{
		if (!preg_match('/^https?:\/\//i', $location))
			$location = self::GetAbsoluteURI().$location;
		$location = str_replace('&amp;', '&', $location);
		if (!headers_sent())
		{
			Header('Location: '.$location);
			die;
		}
		printf('<script type="text/javascript">window.location.href="'.$location.'"</script>'."\n");
		printf('<meta http-equiv="refresh" content="1; url='.$location.'" />');
		die;
	}

	public static function GetHost()
	{
		return sprintf('http%s://%s',
			$_SERVER['SERVER_PORT'] != 80 ? 's' : '',
			$_SERVER['SERVER_NAME']
		);
	}

	public static function GetAbsoluteURI()
	{
		return self::GetHost().rtrim(dirname($_SERVER['PHP_SELF']), '/').'/';
//		return self::GetHost().rtrim(str_replace(self::GetLink(), '', $_SERVER['REQUEST_URI']), '/').'/';
	}

	public static function PopulateRequest($key, $value)
	{
		$_GET[$key] = $_REQUEST[$key] = $value;
		if (!empty($_POST))
			$_POST[$key] = $value;
	}
}
?>