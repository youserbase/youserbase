<?php
class Hook_System extends Hook
{
	public static $hooks = array(
		'System:Wakeup'=>'Wakeup',
		'Config:Options'=>'GetOptions'
	);

	public static function Wakeup()
	{
		if (Config::Get('system', 'maintenance') and !FrontController::IsLocation('System', 'System', 'Maintenance') and !Youser::Is('administrator','root','god'))
		{
			FrontController::Relocate('System', 'System', 'Maintenance');
		}
		elseif (!Config::Get('system', 'maintenance') and FrontController::IsLocation('System', 'System', 'Maintenance'))
		{
			FrontController::Relocate('System', 'System', 'Index');
		}

		if (!Youser::Id() and $GLOBALS['VIA_AJAX'] and FrontController::IsLocation('User', 'AJAX', 'Poll'))
		{
			Header('X-Refresh: true');
			die;
		}
	}

	public static function GetOptions()
	{
		return array(
			'system:harvest_mode:bool',
			'system:maintenance:bool',
			'system:develop_mode:bool',
		);
	}
}
?>