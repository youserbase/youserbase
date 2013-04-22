<?php
class Datasheets_Visits extends Controller 
{
	
	private $ignore = array('%66.249.%', '%77.88.%', '%67.218.116.%', '%87.238.%', '%67.202.54.%', '%82.94.179.%', '%65.55.%', '%75.101.221.%' );
	
	public function Index()
	{
		$template = $this->get_template(true);
		$skip = 0;
		if(Session::Get('skip_visits'))
		{
			$skip = Session::Get('skip_visits');
		}
		$limit = 100;
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		if(Session::Get('visits_year'))
		{
			$year = Session::Get('visits_year');
		}
		if(Session::Get('visits_month'))
		{
			$month = Session::Get('visits_month');
		}
		if(Session::Get('visits_day'))
		{
			$day = Session::Get('visits_day');
		}
		$year_until = date('Y');
		$month_until = date('m');
		$day_until = date('d');
		if(Session::Get('visits_year_until'))
		{
			$year_until = Session::Get('visits_year_until');
		}
		if(Session::Get('visits_month_until'))
		{
			$month_until = Session::Get('visits_month_until');
		}
		if(Session::Get('visits_day_until'))
		{
			$day_until = Session::Get('visits_day_until');
		}
		$timestamp = date($year.'-'.$month.'-'.$day.' 00:00:00');
		$timestamp_until = date($year_until.'-'.$month_until.'-'.$day_until.' 23:59:59');
		
		$query = "";
		$count = DBManager::Get('devices')->query("SELECT COUNT(device_id) FROM device_statistics WHERE timestamp > ? AND timestamp <= ? AND youser_id NOT LIKE '%66.249.%' AND youser_id NOT IN (1,2,3,4,5,6,7,169, 197)", $timestamp, $timestamp_until)->fetch_item();
		$unique = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT(youser_id)) FROM device_statistics WHERE timestamp > ? AND timestamp <= ? AND youser_id NOT LIKE '%66.249.%' AND youser_id NOT LIKE '%77.88.%' AND youser_id NOT LIKE '%67.218.116.%' AND youser_id NOT LIKE '%87.238.%' AND youser_id NOT LIKE '%67.202.54.%' AND youser_id NOT LIKE '%82.94.179.%' AND youser_id NOT LIKE '%65.55.%' AND youser_id NOT LIKE '%75.101.221.%' AND youser_id NOT IN (1,2,3,4,5,6,7, 169,197)", $timestamp, $timestamp_until)->fetch_item();
		$visits = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id, youser_id, language, UNIX_TIMESTAMP(timestamp) as timestamp, tab FROM device_statistics WHERE timestamp > ? AND timestamp <= ?  AND youser_id NOT LIKE '%66.249.%' AND youser_id NOT LIKE '%87.238.%' AND youser_id NOT LIKE '%77.88.%' AND youser_id NOT LIKE '%67.218.116.%' AND youser_id NOT LIKE '%67.202.54.%' AND youser_id NOT LIKE '%65.55.%' AND youser_id NOT LIKE '%82.94.179.%' AND youser_id NOT LIKE '%75.101.221.%' AND youser_id NOT IN (1,2,3,4,5,6,7,169,197) ORDER BY timestamp DESC", $timestamp, $timestamp_until)->to_array();
		$template->assign('skip', $skip);
		$template->assign('limit', $limit);
		$template->assign('total', $count);
		$template->assign('unique', $unique);
		$template->assign('year', $year);
		$template->assign('month', $month);
		$template->assign('day', $day);
		$template->assign('year_until', $year_until);
		$template->assign('month_until', $month_until);
		$template->assign('day_until', $day_until);
		$template->assign('visits', $visits);
		
	}
	
	public function skip()
	{
		if(isset($_REQUEST['skip_stats']))
		{
			Session::Set('skip_visits', $_REQUEST['skip_stats']);
		}
		FrontController::Relocate('Index');
	}
	
	public function change_time()
	{
		if(isset($_POST['year']))
		{
			Session::Set('visits_year', $_POST['year']);
		}
		if(isset($_POST['month']))
		{
			Session::Set('visits_month', $_POST['month']);
		}
		if(isset($_POST['day']))
		{
			Session::Set('visits_day', $_POST['day']);
		}
		if(isset($_POST['year_to']))
		{
			Session::Set('visits_year_until', $_POST['year_to']);
		}
		if(isset($_POST['month_to']))
		{
			Session::Set('visits_month_until', $_POST['month_to']);
		}
		if(isset($_POST['day_to']))
		{
			Session::Set('visits_day_until', $_POST['day_to']);
		}
		FrontController::Relocate('Index');
	}
}
?>