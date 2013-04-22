<?php
class Hook_GarbageCollector extends Hook
{
	public static $hooks = array(
		'System:Shutdown'=>'Collect',
		'Config:Options'=>'GetOptions'
	);

	public static function GetOptions()
	{
		return array(
			'system:gc_probability:range:1,100'
		);
	}

	public static function Collect()
	{
		if (mt_rand(1, 100)>(100-Config::Get('system:gc_probability')))
		{
			Session::CollectGarbage();

			// DEBUG
			if (Youser::Is('root,god'))
			{
//				Dobber::ReportNotice('Garbage collected');
			}
			Event::Dispatch('alert', 'Garbage:Collect');
		}
	}
}