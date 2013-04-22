<?php
class Hook_Beta extends Hook
{
	public static $hooks = array(
//		'FrontController:GetAlias'=>'TeaserIt',
		'Config:Options'=>'GetOptions',
		'System:Wakeup'=>'Wakeup'
	);

	public static function GetOptions()
	{
		return array(
			'system:closed_system:bool',
		);
	}

	public static function Wakeup()
	{
		if (!Config::Get('system', 'closed_system'))
		{
			return;
		}
		if (!$GLOBALS['VIA_AJAX'] and Youser::Id() and FrontController::IsLocation('Beta', 'Teaser'))
		{
			FrontController::Relocate('System', 'System', 'Index');
		}
	}

	public static function TeaserIt($location)
	{
		if (Youser::Id() or !Config::Get('system', 'closed_system') or ($location['module']=='Beta' and $location['controller']=='Teaser'))
		{
			return $location;
		}
		Header('HTTP/1.0 401 Unauthorized');
		Header('Status: 401 Unauthorized');

		$location['module'] = 'Beta';
		$location['controller'] = 'Teaser';
		$location['method'] = 'Index';

		return $location;
	}
}
?>