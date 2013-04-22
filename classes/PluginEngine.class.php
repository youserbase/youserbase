<?php
class PluginEngine
{
	private static $repository = null;
	private static $plugins = array();
	private static $cronjobs = array();

	public static function SetRepository($repository)
	{
		self::$repository = $repository;
	}

	public static function GetRepository()
	{
		if (self::$repository === null)
		{
			self::$repository = array();

			$potential_plugin_files = array_merge(
				glob(APP_DIR.'/controllers/*/plugins/Plugin_*.class.php', GLOB_NOSORT),
				glob(INC_DIR.'/plugins/Plugin_*.class.php', GLOB_NOSORT)
			);

			foreach ($potential_plugin_files as $plugin_file)
			{
				require_once $plugin_file;

				$class_name = basename($plugin_file, '.class.php');
				$plugin_name = preg_replace('/^Plugin_/', '', $class_name);
				$plugin_id = md5($plugin_name);

				$vars = get_class_vars($class_name);

				self::$repository[$plugin_id] = array(
					'class'=>$class_name,
					'name'=>$plugin_name,
					'filename'=>$plugin_file,
					'options'=>isset($vars['options'])
						? $vars['options']
						: array()
					,
					'link'=>isset($vars['link_location'])
						? ((is_string($vars['link_location']) and strpos($vars['link_location'], 'http://')!==false)
							? $vars['link_location']
							: Template::Interpolate(call_user_func_array(array('FrontController', 'GetLink'), $vars['link_location']), $_REQUEST, true))
						: null
					,
					'object'=>null,
				);
				if (is_callable(array($class_name, 'cronjob')))
				{
					self::$cronjobs[$plugin_id] = array(
						'name' => $plugin_name,
						'interval' => isset($vars['cronjob'])
							? $vars['cronjob']
							: 600,
					);
				}
			}
		}
		return self::$repository;
	}

	public static function GetPluginNames()
	{
		$result = array();
		foreach (self::GetRepository() as $index=>$plugin)
			$result[$index] = $plugin['name'];
		return $result;
	}

	public static function GetOptions($plugin = null)
	{
		$options = array();

		$repository = self::GetRepository();
		if ($plugin === null)
			foreach ($repository as $plugin)
				foreach ($plugin['options'] as $option)
					array_push($options, $plugin['name'].':'.$option);
		elseif (isset($repository[md5($plugin)]))
			foreach ($repository[md5($plugin)]['options'] as $option)
				array_push($options, $plugin.':'.$option);

		return $options;
	}

	public static function GetCrons()
	{
		self::GetRepository();
		return self::$cronjobs;
	}

	public static function GetConfig($scope, $key = null)
	{
		return Config::Get($scope, $key, 'plugin');
	}

	public static function SetConfig($scope, $key, $value)
	{
		return Config::Set($scope, $key, $value, 'plugin');
	}

	public static function &GetPlugin($plugin_name, $method = null)
	{
		$plugin_id = md5($plugin_name);

		$repository = self::GetRepository();
		if (!isset($repository[$plugin_id]))
			throw new Exception('Could not find plugin');

		$plugin = $repository[$plugin_id];

		if (!is_object($plugin['object']))
			self::$repository[$plugin_id]['object'] = new $plugin['class']($plugin_name, $method, dirname($plugin['filename']), $plugin['link']);


/*
		$method = rtrim('fill_template_'.$method, '_');
		if (!is_callable(array(self::$repository[$plugin_id]['object'], $method)))
			throw new Exception('Invalid plugin method called');
*/

		return self::$repository[$plugin_id]['object'];
	}

	public static function Render($plugin_name, &$link, &$header, &$wrapper)
	{
		$method = null;
		if (strpos($plugin_name, ':')!==false)
			list($plugin_name, $method) = explode(':', $plugin_name, 2);

		$plugin =& self::GetPlugin($plugin_name, $method);

		$link = $plugin->get_link();
		$header = $plugin->has_header();
		$wrapper = $plugin->has_wrapper();

		$template = $plugin->get_template();
		$result = is_callable(array($plugin, rtrim('fill_template_'.$method, '_')))
			? call_user_func(array($plugin, rtrim('fill_template_'.$method, '_')), $template)
			: call_user_func_array(array($plugin, 'fill_template'), array($template, $method));
		if ($result === false)
			return false;

		$template->parse();
		$template->execute_filters();
		return (string)$template;
	}

	public static function Engage($plugin_name)
	{
		$template = new Template('layouts/plugin.php');
		$plugins = array();

		foreach ((array)$plugin_name as $plugin)
			if (is_array($plugin))
			{
				$plugin_container = array();
				foreach ($plugin as $p)
				{
					$link = null;
					$header = true;
					$wrapper = true;

					if (($content = self::Render($p, $link, $header, $wrapper))===false)
						continue;
					array_push($plugin_container, array(
						'name'=>str_replace(':', '_', $p),
						'content'=>$content,
						'link'=>$link,
						'header'=>$header,
						'wrapper'=>$wrapper,
					));
				}
				array_push($plugins, $plugin_container);
			}
			else
			{
				$link = null;

				try {
					if (($content = self::Render($plugin, $link, $header, $wrapper))===false)
						continue;
				} catch (Exception $e) {
					$title = '[PluginEngine] Error in "'.$plugin.'"';
					$content = nl2br($e);
				}
				array_push($plugins, array(
					'name'=>str_replace(':', '_', $plugin),
					'content'=>$content,
					'link'=>$link,
					'header'=>$header,
					'wrapper'=>$wrapper,
				));
			}

		$template->assign('plugins', $plugins);

		return $template->parse();
	}

	public static function Call($plugin_name, $method)
	{
		$plugin =& self::GetPlugin($plugin_name);

		if (method_exists($plugin, $method))
			call_user_func(array($plugin, $method));

		if ($GLOBALS['VIA_AJAX'])
		{
			$link = '';
			return self::Render($plugin_name, $link, $header, $wrapper);
		}
		FrontController::DirectRelocate($_GET['return_to']);
	}
}
?>