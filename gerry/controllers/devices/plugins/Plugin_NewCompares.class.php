<?php
class Plugin_NewCompares extends Plugin 
{
	
	public static $options = array(
		'display_limit:range:1,10'
	);
	
	public function skip_compare()
	{
		Session::Set('skip_new_compares', $_GET['skip']);
	}
	
	public function fill_template(&$template)
	{
		$skip = 0;
		if (Session::Get('skip_new_compares'))
		{
			$skip = Session::Get('skip_new_compares');
		}
		$compare_ids = DBManager::Get('devices')->skip($skip)->limit($this->get_config('display_limit'))->query("SELECT compare_id, devices FROM compares ORDER BY timestamp DESC")->to_array('compare_id', 'devices');
		foreach ($compare_ids as $compare_id => $devices){
			$compares[$compare_id] = comparelist::get_compare_devices($compare_id);
		}
		
		$total = count($compares);
		$template->assign('compares', $compares);
		$template->assign('limit', $this->get_config('display_limit'));
		$template->assign('skip', $skip);
		$template->assign('total', $total);

		return true;
	}
}
?>