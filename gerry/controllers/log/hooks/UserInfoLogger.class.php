<?php
class UserInfoLogger extends Hook
{
	public static $hooks = array(
		'System:Wakeup'=>'LogInfo'
	);

	public static function LogInfo()
	{
		if (!empty($GLOBALS['deny_log']))
		{
			return;
		}

		DBManager::Get()->query("INSERT DELAYED INTO log_hits (ip, session_id, youser_id, timestamp, location, get_parameters, post_parameters, user_agent, language, via_ajax) VALUES (INET_ATON(?), ?, ?, NOW(), ?, ?, ?, ?, ?, ?)",
			$_SERVER['REMOTE_ADDR'],
			Session::Id(),
			Youser::Id(),
			implode('/', FrontController::GetLocation()),
			empty($_GET) ? null : serialize($_GET),
			empty($_POST) ? null : serialize($_POST),
			$_SERVER['HTTP_USER_AGENT'],
			BabelFish::GetLanguage(),
			$GLOBALS['VIA_AJAX'] ? 'yes' : 'no'
		);
	}
}
?>