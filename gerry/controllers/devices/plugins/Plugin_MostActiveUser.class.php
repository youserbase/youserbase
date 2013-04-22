<?php
class Plugin_MostActiveUser extends Plugin
{
/*
	public static $options = array(
		'display_limit:range:1,40'
	);
*/
	public function fill_template(&$template)
	{
		$youser_id = DBManager::Get()->limit(1)->query(
			"SELECT object_id
			 FROM activities
			 WHERE timestamp BETWEEN TIMESTAMPADD(HOUR, -480, NOW()) AND NOW()
			 AND object_id != 107
			 GROUP BY object_id
			 ORDER BY COUNT(*) DESC"
		)->to_array('object_id', 'object_id');

		
		if (!$youser_id)
		{
			return false;
		}

		$template->assign('youser_id', $youser_id);

		return true;
	}
}
?>