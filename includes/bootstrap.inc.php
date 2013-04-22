<?php
/*
	if (reset(sys_getloadavg()) > 90) // 0.9 = 90%
	{
	    header('HTTP/1.1 503 Too busy, try again later');
	    die('Server too busy. Please try again later.');
	}
*/
	ini_set('error_log', realpath(dirname(__FILE__).'/..').'/log/php_error.log');
	ini_set('log_errors', true);
	ini_set('precision', 16);

	ini_set('max_execution_time', false);
	set_time_limit(0);

	error_reporting(E_ALL);

	// Set ini params
	ini_set('session.use_only_cookies', '1');
	ini_set('session.use_trans_sid', false);
	ini_set('url_rewrite.tags', '');
	ini_set('pcre.backtrack_limit', 250000);
	ini_set('date.timezone', 'Europe/Berlin');
//	date_default_timezone_set('Europe/Berlin');

	define('TOKEN_LIFETIME', 30*60);

	mt_srand((double)microtime()*1000000);

	$GLOBALS['VIA_AJAX'] = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') or !empty($_REQUEST['AJAX_MODE']);

	$directory = dirname(__FILE__).'/';
	// Load standard classes to avoid too many classloader calls
	require $directory.'../classes/Assets.class.php';
	require $directory.'../classes/BabelFish.class.php';
	require $directory.'../classes/Cache.class.php';
	require $directory.'../classes/ClassLoader.class.php';
	require $directory.'../classes/Config.class.php';
	require $directory.'../classes/Controller.class.php';
	require $directory.'../classes/Cookie.class.php';
	require $directory.'../classes/DBManager.class.php';
	require $directory.'../classes/database/DBLayer.class.php';
	require $directory.'../classes/database/DBLayer_Result.class.php';
	require $directory.'../classes/database/mysql/DBLayer_mysql.class.php';
	require $directory.'../classes/database/mysql/DBLayer_mysql_Result.class.php';
	require $directory.'../classes/Dispatcher.class.php';
	require $directory.'../classes/Dobber.class.php';
	require $directory.'../classes/Event.class.php';
	require $directory.'../classes/FrontController.class.php';
	require $directory.'../classes/Hook.class.php';
	require $directory.'../classes/Location.class.php';
	require $directory.'../classes/Locale.class.php';
	require $directory.'../classes/MemoryShelf.class.php';
	require $directory.'../classes/PluginEngine.class.php';
	require $directory.'../classes/Plugin.class.php';
	require $directory.'../classes/Session.class.php';
	require $directory.'../classes/Template.class.php';
	require $directory.'../classes/TagParser.class.php';
	require $directory.'../classes/Timer.class.php';

	require $directory.'/config.inc.php';
	require $directory.'/classloader.inc.php';

	$bootstrap_filename = Cache::GetDirectory('system').'/essentials.inc.php';
	if (!file_exists($bootstrap_filename))
	{
		if (class_exists('UnsinnigerKlassenname'))
			throw new Exception('Jaaa genau');
		Event::RegisterHooks(
			'../includes/hooks',
			'./controllers/*/hooks'
	//		'./controllers/*/plugins'
		);
		$classes = array_merge(ClassLoader::GetClasses(), Event::GetFiles());

		$essentials = "<?php\n";
		$essentials .= "ClassLoader::SetClasses(unserialize('".serialize($classes)."'));\n";
		$essentials .= "Controller::SetControllers(unserialize('".serialize(Controller::GetControllers())."'));\n";
		$essentials .= "Event::SetHooks(unserialize('".serialize(Event::GetHooks())."'));\n";
		$essentials .= "PluginEngine::SetRepository(unserialize('".serialize(PluginEngine::GetRepository())."'));\n";
		$essentials .= "if (class_exists('Hook_TagReplacer')) Hook_TagReplacer::SetReplacers(unserialize('".serialize(Hook_TagReplacer::GetReplacers())."'));\n";
		file_put_contents($bootstrap_filename, $essentials);
	}
	require $bootstrap_filename;
	if (!Config::Get('system', 'develop_mode'))
		@include Cache::GetDirectory('system').'/essentials.cache.php';

	require $directory.'/functions.inc.php';
	require $directory.'/locales.inc.php';
	require $directory.'../classes/vendor/simplepie.inc';

	function our_exception_handler($exception)
	{
		Dobber::ReportSystemError('Uncaught exception %s', $exception->getMessage());
		Dobber::ReportSystemError('<pre>%s</pre> ', $exception->getTraceAsString());
		Dobber::ReportSystemError('<pre>%s</pre> ', print_r($exception, true));
		FrontController::Relocate('System', 'System', 'Error');
	}
	$old_exception_handler = set_exception_handler('our_exception_handler');

	function our_error_handler($error_number, $error_string, $error_file, $error_line, $error_context)
	{
		if ($error_number & error_reporting())
		{
			Dobber::ReportSystemError('Error: #%d - %s in %s on line %d',
				$error_number,
				$error_string,
				$error_file,
				$error_line
			);
			$dbt = debug_backtrace();
			foreach ($dbt as &$item)
			{
				unset($item['args'], $item['object']);
			}
			Dobber::ReportSystemError('<pre>%s</pre>',
				print_r($dbt, true)
			);
		}
	}
	$old_error_handler = set_error_handler('our_error_handler');

	function restore_error_handlers()
	{
		restore_error_handler();
		restore_exception_handler();
	}

	if (get_magic_quotes_gpc() or get_magic_quotes_runtime())
	{
		function stripslashes_deep($value)
		{
			return is_array($value)
				? array_map(__FUNCTION__, $value)
				: stripslashes($value);
    	}

    	$_POST = array_map('stripslashes_deep', $_POST);
    	$_GET = array_map('stripslashes_deep', $_GET);
    	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
	}
?>