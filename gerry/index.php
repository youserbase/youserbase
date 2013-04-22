<?php
	if (!defined('DISPATCHED') OR !DISPATCHED)
		require '../includes/bootstrap.inc.php';

	if (isset($_REQUEST['redirect_uri']))
		FrontController::DirectRelocate(urldecode($_REQUEST['redirect_uri']));

	if (isset($_GET['clear']))
		Dobber::getMessages();

	Timer::Report('First blink');
	Event::Dispatch('alert', 'System:Wakeup');

	try
	{
		$controller = FrontController::Get();
	}
	catch (FrontControllerException $e)
	{
		var_dump($e);
		$controller = FrontController::Get('System', 'System', 'Page_404', array($e->get_location()));
	}

	Timer::Report('GotController');

	$template = $controller->execute()->get_template();

	Timer::Report('ExecutedController');

	// Revert back to default scope if any controller changed the db scope
	DBManager::SelectScope();

	$PAGE_TITLE = 'PAGETITLE_'.($template->get_variable('PAGE_TITLE_APPEND')
		? md5(FrontController::GetLocationHash().$template->get_variable('PAGE_TITLE_APPEND'))
		: FrontController::GetLocationHash());
	$template->assign('PAGETITLE', $PAGE_TITLE);
	if (!isset($_REQUEST['REMOVE_LAYOUT']) and !$GLOBALS['VIA_AJAX'])
	{
		if (!$template->has_layout())
			$template->set_layout('layouts/gerry');
		$template->assign('dobber', Dobber::getMessages());
		$template->assign('body_id', strtolower(implode('_', FrontController::GetLocation())));

		$browscap = new Browscap(Cache::GetDirectory());
		$browser = $browscap->getBrowser();
		$template->assign('BROWSER_STRING', strtolower($browser->Browser.$browser->MajorVer));
		$template->assign('languages', BabelFish::GetLanguages());

		if ($controller->get_navigation())
			$template->assign('CONTROLLER_NAVIGATION', $controller->get_navigation());
	}
	else
	{
//		$template->set_layout('layouts/gerry_ajax');
		$dobber = array();
		foreach (Dobber::getMessages(null, 'system_error') as $type=>$messages)
		{
			$dobber[$type] = array(
				'header' => BabelFish::Get('DOBBER_'.strtoupper($type).'_LABEL'),
				'messages' => array(),
			);
			foreach ((array)$messages as $message)
				array_push($dobber[$type]['messages'], is_string($message)
					? $message.'!'
					: Template::Interpolate(BabelFish::Get($message['message']), $message['parameters']).'!'
				);
		}
		if (count($dobber))
			Header('X-Dobber: '.json_encode($dobber));
		if (!FrontController::IsLocation('User', 'AJAX', 'Poll') and !FrontController::IsLocation('Dock', 'Dropbox'))
			Header('X-Title: '.rawurlencode($template->evaluate(BabelFish::Get($PAGE_TITLE))));
	}

	$template->register_filter('stripwhitespace');
	$template->register_filter('utf8');
	$template->register_filter('gzip');
	$template->register_filter('setlanguage', BabelFish::GetLanguage());

	Timer::Report('Displaytemplate');

	$template->display();

	if (!Config::Get('system', 'develop_mode') and !empty($GLOBALS['STORE_ASSETS_CACHE']))
//	if (!Config::Get('system', 'develop_mode') and (!empty($GLOBALS['STORE_ASSETS_CACHE']) or !empty(Dispatcher::GetCache())) )
	{
		$essentials_cache = '<?php'."\n";
		$essentials_cache .= '$GLOBALS[\'ASSETS_CACHE\']=unserialize(\''.serialize($GLOBALS['ASSETS_CACHE']).'\');'."\n";
/*
	This seems to bug if there are too many writes occuring
		$essentials_cache .= 'Dispatcher::SetCache(unserialize(\''.serialize(Dispatcher::GetCache()).'\'));'."\n";
*/
		file_put_contents(Cache::GetDirectory('system').'/essentials.cache.php', $essentials_cache, LOCK_EX);
	}

	Event::Dispatch('alert', 'System:Shutdown');
?>
