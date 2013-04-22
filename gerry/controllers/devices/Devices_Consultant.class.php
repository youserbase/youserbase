<?php
class Devices_Consultant extends Controller
{
	const show_only_pictures = true;
	const max_devices = 24;

	private function GetDevices($exclude = array())
	{
		$filter = array();
		foreach (array('manufacturer_id', 'shape:LIKE', 'input_method') as $column)
		{
			list($column, $command) = array_slice(explode(':', $column.':='), 0, 2);
			if (isset($_POST[$column]) and $_POST[$column]!=-1)
			{
				$filter[$column] = ($command=='LIKE'
					? array('LIKE'=>'%'.$_POST[$column].'%')
					: array($command=>$_POST[$column]));
			}
		}
		if (self::show_only_pictures)
		{
			$filter['pictures'] = array('>'=>0);
		}

		$arguments = array();

		$query = "SELECT device_id, manufacturer_id, shape, input_method FROM temp_consultant WHERE shape NOT IN ('', 'SHAPE_CLOCK', 'SHAPE_WATCH') ##FILTER## ORDER BY rating DESC, RAND()";

		$replace = '';
		if (!empty($filter))
		{
			$replace = array();
			foreach ($filter as $field=>$value)
			{
				$command = key($value);
				$value = reset($value);

				array_push($replace, $field.' '.$command.' ? ');
				array_push($arguments, $value);
			}

			$replace = ' AND '.implode(' AND ', $replace);
		}

		$query = str_replace('##FILTER##', $replace, $query);

		array_unshift($arguments, $query);

		$result = call_user_func_array(array(DBManager::Get()->limit(self::max_devices), 'query'), $arguments);
		$devices = $result->to_array('device_id');

		foreach ($exclude as $device_id)
		{
			unset($devices[$device_id]);
		}

		return array_slice($devices, 0, self::max_devices - count($exclude));
	}

	public function Index_AJAX()
	{
		$map = array(
			'm'=>'manufacturer_id',
			'i'=>'input_method',
			's'=>'shape'
		);
		foreach ($map as $key => $value)
		{
			if (isset($_POST[$key]))
			{
				$_POST[$value] = $_POST[$key];
			}
		}

		$devices = $this->GetDevices(empty($_REQUEST['device_ids'])?array():$_REQUEST['device_ids']);

		$template = $this->get_template(true);
		$template->assign('devices', $devices);
	}

	public function Index()
	{
		$devices = $this->GetDevices();

		$shapes = self::GetColumn('shape');
		$temp = array();
		foreach ($shapes as $index=>$shape)
		{
			$temp = array_merge($temp, explode(',', $shape));
		}
		$shapes = array_unique($temp);

		$template = $this->get_template(true);
		$template->assign('devices', $devices);

		$template->assign('manufacturers', self::GetColumn('manufacturer_name', 'manufacturer_id'));
		$template->assign('shapes', $shapes);
		$template->assign('input_methods', self::GetColumn('input_method'));
		$template->assign('max_devices', self::max_devices);
	}

	private static function GetColumn($column, $index_column=null)
	{
		return $index_column === null
			? DBManager::Get()->query("SELECT DISTINCT {$column} FROM temp_consultant WHERE {$column} IS NOT NULL AND shape NOT IN ('SHAPE_CLOCK', 'SHAPE_WATCH', '') ".(self::show_only_pictures ?' AND pictures>0 ':'')." ORDER BY {$column} ASC")->to_array(null, $column)
			: DBManager::Get()->query("SELECT DISTINCT {$index_column}, {$column} FROM temp_consultant WHERE {$column} IS NOT NULL AND shape NOT IN ('SHAPE_CLOCK', 'SHAPE_WATCH', '') ".(self::show_only_pictures ?' AND pictures>0 ':'')." ORDER BY {$column} ASC")->to_array($index_column, $column);
	}
}
?>