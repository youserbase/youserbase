<?php
class Ratings_Display extends Controller
{
	private $rating_steps = array(1 => 'WORST', 2 => 'BAD', 3 => 'AVERAGE', 4 => 'GOOD', 5 => 'BEST');
	 
	public function Index()
	{
		$table = false;
		$sheet_tab = null;
		if(isset($_GET['sheet_tab']))
		{
			$sheet_tab = $_GET['sheet_tab'];
		}
		$template = $this->get_template(true);
		$template->assign('sheet_tab', $sheet_tab);
		if(isset($_GET['device_id']))
		{
			
			$device_id = $_GET['device_id'];
			$template->assign('device_id', $device_id);
			if(isset($_GET['table']))
			{
				$table = $_GET['table'];
				$table_rating = getratings::get_table_rating($device_id, $table);
				$feature_rating = getratings::get_feature_rating($device_id, $table);
				$template->assign('table_rating', $table_rating);
				$template->assign('feature_rating', $feature_rating);
				$template->assign('table', $table);
			}
			else if(isset($_GET['tab']))
			{
				$tab = $_GET['tab'];
				if(strtolower($tab) != 'device_rating')
				{
					$tab_rating = getratings::get_tab_rating($device_id, $tab);
					$template->assign('tab_rating', $tab_rating);
					$template->assign('tab', $tab);
				}
				else
				{
					$device_rating = getratings::get_device_rating($device_id);
					$template->assign('device_rating', $device_rating);
					$template->assign('tab', $tab);
				}
			}
			else return false;
		}
		else return false;
	}
	
	public function Edit()
	{
		$template = $this->get_template(true);
		$youser_id = self::get_youser_id();
		$sheet_tab = null;
		if(isset($_GET['sheet_tab']))
		{
			$sheet_tab = $_GET['sheet_tab'];
		}
		$template = $this->get_template(true);
		$template->assign('sheet_tab', $sheet_tab);
		if(isset($_GET['device_id']))
		{
			$device_id = $_GET['device_id'];
			$template->assign('device_id', $device_id);
			$template->assign('rating_steps', $this->rating_steps);
			if(isset($_GET['table']))
			{
				$table = $_GET['table'];
				$table_rating = getratings::get_table_rating($device_id, $table, $youser_id);
				$feature_rating = getratings::get_feature_rating($device_id, $table, $youser_id);
				$template->assign('table_rating', $table_rating);
				$template->assign('feature_rating', $feature_rating);
				$template->assign('table', $table);
			}
			else if(isset($_GET['tab']))
			{
				$tab = $_GET['tab'];
				if(strtolower($tab) != 'device_rating')
				{
					$tab_rating = getratings::get_tab_rating($device_id, $tab, $youser_id);
					$template->assign('tab_rating', $tab_rating);
					$template->assign('tab', $tab);
				}
				else
				{
					$device_rating = getratings::get_device_rating($device_id);
					$template->assign('tab', $tab);
					$template->assign('device_rating', $device_rating);
				}
			}
			else return false;
		}
		else return false;
	}
	
	public function Save()
	{
		if(isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
			if(isset($_REQUEST['tab']))
			{
				$tab = $_REQUEST['tab'];
				$rating = $_REQUEST['rating'];
				if(isset($_REQUEST['table']) && $_REQUEST['table'] !== 'null')
				{
					save_ratings::save_table_rating($device_id, $_REQUEST['table'], $rating);
					save_ratings::update_tab_rating($device_id);
					save_ratings::update_device_rating($device_id);
					FrontController::Relocate('datasheets', 'datasheets', 'phonesheet', array('device_id' => $device_id, 'tab' => $tab));	
				}
				else 
				{
					switch ($tab)
					{
						case 'device_rating':
							save_ratings::save_device_rating($device_id, $rating);
							save_ratings::update_tab_rating($device_id, $rating);
							break;
					}
				}
				FrontController::Relocate('datasheets', 'datasheets', 'phonesheet', array('device_id' => $device_id, 'tab' => $tab));
			}
		}
		FrontController::Relocate('datasheets', 'datasheets', 'phonesheet', array('device_id' => $device_id, 'tab' => $tab));
		
	}
	
	private static function get_youser_id()
	{
		$youser_id = md5($_SERVER['REMOTE_ADDR']);
		if(Youser::Id() !== null)
		{
			$youser_id =  Youser::Id();
		}
		return $youser_id;
	}
}
?>