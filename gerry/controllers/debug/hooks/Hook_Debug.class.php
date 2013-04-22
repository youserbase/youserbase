<?php
class Hook_Debug extends Hook
{
	public static $hooks = array(
		'System:Wakeup:Before'=>'Initialize',
		'Template:Display'=>'AddConsole',
	);

	public static function Initialize()
	{
		Timer::Report('Wakeup');
		if (Session::Get('debugmode'))
		{
			$scopes = DBManager::GetScopes();
			foreach ($scopes as $scope)
				DBManager::Get($scope)->log_queries(true);
		}
		ob_start();
	}

	public static function AddConsole($content)
	{
		$output = ob_get_clean();

		if (!Youser::Is('god,root'))
			return $content;

		$template = self::get_template('debug');
		$template->assign('debug', Session::Get('debugmode'));
		if ($template->get_variable('debug'))
		{
			$template->assign('console', $output);
			$template->assign('query_count', DBManager::GetQueryCount());
			$template->assign('queries', DBManager::GetQueries());
			$template->assign('events', Timer::GetEvents());
			$template->assign('duration', Timer::GetDuration());

			$hooks = Event::GetHooks();
			$hook_count = 0;
			foreach ($hooks as $events)
			{
				foreach ($events as $data)
				{
					$hook_count += count($data);
				}
			}

			$template->assign('hooks', $hooks);
			$template->assign('event_count', count($hooks));
			$template->assign('hook_count', $hook_count);
		}

		$debug_infos = $template->render();

		$content = str_replace('</body>', $debug_infos.'</body>', $content);

		return $content;
	}
}
?>