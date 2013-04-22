<?php
class Hook_Search extends Hook
{
	public static $hooks = array(
		'Garbage:Collect'=>'CollectGarbage',
		'Config:Options'=>'GetOptions',
		'Search:Searched'=>'LogSearch'
	);

	public static function GetOptions()
	{
		return array(
			'search:pagination_count:range:5,30,5',
			'search:result_lifetime:range:1,30'
		);
	}

	public static function CollectGarbage()
	{
		DBManager::Get()->query("DELETE FROM search_results WHERE timestamp<TIMESTAMPADD(MINUTE, -".Config::Get('search:result_lifetime').", NOW())");
	}

	public static function LogSearch($needle)
	{
		DBManager::Get()->query("INSERT INTO log_searches (ip, session_id, youser_id, timestamp, needle) VALUES (INET_ATON(?), ?, ?, NOW(), ?)",
			$_SERVER['REMOTE_ADDR'],
			Session::Id(),
			Youser::Id(),
			$needle
		);
	}
}
?>