<?php
class Datasheets_Links extends Controller 
{
	private $page_type = array('MANUFACTURER_SITE', 'BLOG', 'MAGAZINE', 'FORUM');
	private $content_type = array('DESCRIPTION', 'OPINION', 'TIPS_TRICKS');
	
	public function Index($device_id = null)
	{
		if($device_id == null && isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
		}
		if($device_id == null)
		{
			return false;
		}
		$links_count = DBManager::Get('devices')->query("SELECT COUNT(device_links_id) FROM device_links WHERE device_id = ?;", $device_id)->fetch_item();
		$links = links::get_links($device_id);
		$helpfull = links::get_helpfull($links);
		$links_ol = links::get_links($device_id, null, BabelFish::GetLanguage());
		$languages = BabelFish::GetLanguages();
		$template = $this->get_template(true);
		$template->assign('device_id', $device_id);
		$template->assign('links', $links);
		$template->assign('links_count', $links_count);
		$template->assign('helpfull', $helpfull);
		$template->assign('links_ol', $links_ol);
		$template->assign('page_type', $this->page_type);
		$template->assign('content_type', $this->content_type);	
		$template->assign('languages', $languages);
	}
	
	public function Admin()
	{
		
	}
	
	public function new_link()
	{
		if(!isset($_REQUEST['device_id']))
		{
			return false;
		}
		$device_id = $_REQUEST['device_id'];
		$device_id_int = $device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?", $device_id)->fetch_item();
		links::set_links($device_id, $device_id_int);
		return true;
	}
	
	public function delete()
	{
		if(isset($_REQUEST['device_links_id']))
		{
			$device_links_id = $_REQUEST['device_links_id'];
			DBManager::Get('devices')->query("DELETE FROM device_links WHERE device_links_id = ?", $device_links_id);
			DBManager::Get('devices')->query("DELETE FROM device_links_helpfull WHERE device_links_id = ?", $device_links_id);
			return true;
		}
	}
	
	public function helpfull()
	{
		if(!isset($_REQUEST['device_links_id']))
		{
			return false;
		}
		$device_links_id = $_REQUEST['device_links_id'];
		if(isset($_REQUEST['pro'])) 
		{
			$pro = $_REQUEST['pro'];
		}
		if($pro == 1)
		{
			$helpfull = 1;
			$not_helpfull = DBManager::Get('devices')->query("SELECT SUM(not_helpfull) FROM device_links_helpfull WHERE device_links_id = ?;", $device_links_id)->fetch_item();
			Dobber::ReportNotice($not_helpfull);
			$dlh = new device_links_helpfull();
			$dlh->device_links_id = $device_links_id;
			$dlh->youser_id = Youser::Id();
			$dlh->helpfull = $helpfull;
			$dlh->not_helpfull = $not_helpfull;
			$dlh->timestamp = 'NOW()';
			$dlh->save();
			return true;
		}
		if($pro == -1) 
		{
			$not_helpfull = 1;
			$helpfull = DBManager::Get('devices')->query("SELECT SUM(helpfull) FROM device_links_helpfull WHERE device_links_id = ?;", $device_links_id)->fetch_item();
			$dlh = new device_links_helpfull();
			$dlh->device_links_id = $device_links_id;
			$dlh->youser_id = Youser::Id();
			$dlh->helpfull = $helpfull;
			$dlh->not_helpfull = $not_helpfull;
			$dlh->timestamp = 'NOW()';
			$dlh->save();
			return true;
		}
	}
}
?>