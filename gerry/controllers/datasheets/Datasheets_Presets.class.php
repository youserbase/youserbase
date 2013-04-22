<?php
class Datasheets_Presets extends Controller 
{
	public function Index()
	{
		$tables = getTableInfo::showTables();
		$components_count = count($tables);
		$skip = 0;
		$limit = 20;
		if(isset($_REQUEST['skip_components']))
		{
			$skip = $_REQUEST['skip_components']+$limit;
			
		}
		if(isset($_REQUEST['limit']))
		{
			$limit = $_REQUEST['limit'];
		}
		$template = $this->get_template(true);
		foreach ($tables as $table)
		{
			foreach ($table as $table_name)
			{
				if(strpos($table_name, '_type') !== false && strpos($table_name, 'device') === false)
				{
					$presets[BabelFish::Get(strtoupper($table_name))] = $table_name;
				}
			}
		}
		$presets[BabelFish::Get(strtoupper('manufacturer'))] = 'manufacturer';
		ksort($presets);
		$presets = array_splice($presets, $skip, $limit, true);
		$template->assign('components_count', $components_count);
		$template->assign('skip_components', $skip);
		$template->assign('tables', $presets);
	}
	
	public function Component()
	{
		$template = $this->get_template(true);
		if(isset($_REQUEST['table_name']))
		{
			$table_name = $_REQUEST['table_name'];
		}
		$object = call_user_func_array(array($table_name, 'Get'), array());
		$data = $object->Load();
		$template->assign('table_name', $table_name);
		$template->assign('data', $data);
	}
	
	public function Edit()
	{
		$template = $this->get_template(true);
		$new = false;
		if(isset($_REQUEST['table_name']))
		{
			$table_name = $_REQUEST['table_name'];
		}
		if(isset($_REQUEST['primary']))
		{
			$primary_id = $table_name.'_id';
			$primary = $_REQUEST['primary'];
			$object = call_user_func_array(array($table_name, 'Get'), array());
			$data = $object->Load($primary);
			$template->assign('data', $data);
		}
		else
		{
			$object = call_user_func_array(array($table_name, 'Get'), array());
			$data = $object->Load();
			$template->assign('data', $data);
			$new = true;
		}
		$template->assign('new', $new);
		$template->assign('table_name', $table_name);
	}
	
	public function Save()
	{
		if(isset($_REQUEST['table_name']))
		{
			$table = $_REQUEST['table_name'];
			$table_id = $table.'_id';
			$object = call_user_func_array(array($table, 'Get'), array());
			foreach ($_REQUEST as $key => $value)
			{
				$object->$key = $value;
			}
			$object->$table_id = md5(uniqid(time(true)));
			$object->timestamp = 'NOW()';
			$object->youser_id = Youser::Id();
			$object->save();
		}
		FrontController::Relocate('Component', array('table_name' => $_REQUEST['table_name']));
	}
	
	public function Delete()
	{
		if(isset($_REQUEST['table_name']))
		{
			$table_name = $_REQUEST['table_name'];
			$primary_id = $table_name.'_id';
			if(isset($_REQUEST['primary']))
			{
				$primary_id = $table_name.'_id';
				$primary = $_REQUEST['primary'];
				DBManager::Get('devices')->query("DELETE FROM $table_name WHERE $primary_id = ?;", $primary);
			}
		}
		FrontController::Relocate('Component', array('table_name' => $_REQUEST['table_name']));
	}
	
}
?>