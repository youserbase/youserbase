<?php
class newGenerator extends Controller
{
	public function Index()
	{
		$template = $this->get_template(true);
		if(!empty($_REQUEST['count']))
		{
			$template->assign('count', $_REQUEST['count']);
		}
	}
	
	public function getClasses()
	{
		$template = $this->get_template(true);
		$tables = getTableInfo::showTables();
		$count = 0;
		$count_classes = 0;
		foreach ($tables as $table)
		{
			foreach ($table as $db => $table_name)
			{
				if($table_name !== 'device' && $table_name != 'body' && $table_name != 'body_type' && $table_name != 'operatingsystem' && $table_name != 'operatingsystem_type' && $table_name != 'mobilephone' && $table_name != 'indication_type' && $table_name != 'market_information')
				{
					$table_description = getTableInfo::describeTable($table_name);
					if(strpos($table_name, '_type') != false || $table_name == 'manufacturer' && strpos($table_name, 'device') === false)
					{
						$count ++;
						generatePreset::generate($table_name, $table_description);
					}
					else if(strpos($table_name, 'device') === false && strpos($table_name, 'mapping') === false)
					{
						$count++;
						generateClasses::generate($table_name, $table_description);
					}
					else
					{
						$generator = new generator();
						$generator->function_write_file($table_name);
					}
				}
			}
		}
		FrontController::Relocate('Index', array('count' => $count));
	}
	
	public function Test()
	{
		$object = battery::Get()->Loadview('6d1e18b27a631c074c33335acd004530');
		var_dump($object).die();
	}
	
	public function updateBattery()
	{
		$battery_type_ids = DBManager::Get('devices')->query("SELECT battery_type_id, battery_type_name FROM battery_type")->to_array('battery_type_name', 'battery_type_id');
		
		foreach ($battery_type_ids as $name => $id)
		{
			DBManager::Get('devices')->query("UPDATE battery SET battery_type_id = ? WHERE battery_type_name = ?;", $id, $name);
		}
		FrontController::Relocate('Index', array('count' => $count, 'count_classes' => $count_classes));
	}
}
?>