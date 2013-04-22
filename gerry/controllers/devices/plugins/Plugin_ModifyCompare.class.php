<?php
class Plugin_ModifyCompare extends Plugin
{
	public function fill_template(&$template)
	{
		$tab = 'common';
		if(isset($_GET['tab']))
		{
			$tab = $_GET['tab'];
		}
		$sheet = sheetConfig::$sheet;

		$mod = array();
		foreach ($sheet as $tab => $nope)
		{
			$mod[$tab] = 1;
			if(Session::Get($tab.'_rating_mod'))
			{
				$mod[$tab] = Session::Get($tab.'_rating_mod');
			}
		}

		$template->assign('mod_values', array(0, 1, 2, 3));
		$template->assign('sheet', $sheet);
		$template->assign('tab_mods', $mod);
		$template->assign('tab', $tab);
	}

}
?>