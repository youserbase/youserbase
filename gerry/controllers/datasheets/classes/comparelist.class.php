<?php
class comparelist
{
	public static function add_device($device_id = null)
	{
		if($device_id === null)
		{
			if(!isset($_GET['device_id']))
			{
				return false;
			}
			$device_id = $_GET['device_id'];
		}
		$device_ids = array();
		if(Session::Get('compare'))
		{
			$device_ids = Session::Get('compare');
		}
		if(!in_array($device_id, $device_ids))
		{
			array_push($device_ids, $device_id);
			Session::Set('compare', $device_ids);
		}

	}
	
	public static function get_compare_devices($compare_id){
		$device_ids_int = explode('_', DBManager::Get('devices')->query('SELECT devices FROM compares WHERE compare_id = ?;', $compare_id)->fetch_item());
		foreach ($device_ids_int as $device_id_int){
			$device_ids[] = DBManager::Get('devices')->query('SELECT device_id FROM device WHERE device_id_int = ?', $device_id_int)->fetch_item();
		}
		return $device_ids;
	}
	
	public static function remove_device($device_id = null)
	{
		$new_list = array();
		if($device_id === null)
		{
			if(!isset($_GET['device_id']))
				return false;
			$device_id = $_GET['device_id'];
		}
		$compare_list = Session::Get('compare');
		Session::Clear('compare');
		foreach($compare_list as $id)
		{
			if($id !== $device_id)
			{
				$new_list[] = $id;
			}
		}
		Session::Set('compare', $new_list);
	}
}
?>