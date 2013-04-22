<?php
class Datasheets_Compare extends Controller
{
	private $mod_values = array(0, 1, 2, 3);
	
	public function Index()
	{
		if(!isset($_GET['compare_id'])){
			if (isset($_POST['compare']) && is_array($_POST['compare'])){
				$compare_id = $this->get_compare($_POST['compare']);
				FrontController::Relocate('Index', array('compare_id' => $compare_id));
			}
		}
		$compare_id = $_GET['compare_id'];
		$device_ids = explode('_', DBManager::Get('devices')->query('SELECT devices FROM compares WHERE compare_id = ?;', $compare_id)->fetch_item());
		
		$tab = 'common';
		if(isset($_GET['tab'])){
			$tab = $_GET['tab'];
		}
		
		$template = $this->get_template(true);
		DBManager::Get('devices')->query("UPDATE compares SET views = views+1 WHERE compare_id = ?;", $compare_id);
		device_stats::update_stats($compare_id, '', $tab);
		$template->assign('compare_id', $compare_id);
		$template->assign('device_ids', $device_ids);
		$sheet = sheetConfig::get_sheet();
		unset($sheet['COMMENTS']);
		$template->assign('sheet' , $sheet);
		$template->assign('tab' , $tab);
	}


	public function datasheet($tab = null)
	{
		$tab = 'common';
		if(isset($_GET['tab'])){
			$tab = $_GET['tab'];
		}
		if(!isset($_REQUEST['compare_id'])){
			FrontController::Relocate();
		}
		$compare_id = $_GET['compare_id'];
		$device_ids = comparelist::get_compare_devices($compare_id);
		$device_names = array();
		foreach($device_ids as $device_id)
		{
			$tmp = array(null, null);
			$data[$device_id] = phoneConfig::startDataSheetBuilding(phoneConfig::get_sheet($device_id), $tab, $device_id, 'single', 0);
			$build_in[$device_id] = investigator::get_build_in($device_id);
			if($build_in[$device_id] != 'no')
			{
				$build_in_all = true;
			}
			$device_names[] = BabelFish::Get(DBManager::Get('devices')->query("SELECT device_names_name FROM device_names WHERE device_id = ?;", $device_id)->fetch_item());
		}
		$device_names = implode(', ', $device_names);
		$rating = getratings::rating($device_ids, $tab);
		$template = $this->get_template(true);
		$template->assign('meta', BabelFish::Get('COMPARE').' '.$device_names);
		$template->assign('compare_id', $compare_id);
		$template->assign('build_in', $build_in);
		$template->assign('build_in_all', $build_in_all);
		$template->assign('tab', $tab);
		$template->assign('data' , $data);
		$template->assign('device_ids', $device_ids);
		$template->assign('rating', $rating);
	}
	
	public function get_compare($device_ids)
	{
		$ids = array();
		foreach ($device_ids as $id){
			$device_id_int = Device::getdevice_id_int($id);
			$ids[] = $device_id_int;
		}
		asort($ids);
		$ids = implode('_', $ids);
		$compare_id = DBManager::Get('devices')->query("SELECT compare_id FROM compares WHERE compare_id = ?;", md5($ids))->fetch_item();
		if($compare_id == null)
		{
			if(!($youser_id = Youser::Id())){
				$youser_id = 107;
			}
			$compare_id = md5($ids);
			DBManager::Get('devices')->query("INSERT INTO compares (compare_id, devices, youser_id) VALUES(?, ?, ?);", $compare_id, $ids, Youser::Id());
		}
		return $compare_id;
	}
}
?>