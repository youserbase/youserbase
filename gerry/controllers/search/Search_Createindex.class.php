<?php
class Search_Createindex extends Controller 
{
	public function Index()
	{
		$template = $this->get_template(true);
		if(isset($_REQUEST['result']))
		{
			$template -> assign('result', $_REQUEST['result']);
		}
	}

	
	public function Search()
	{
		$skip = 0;
		$limit = 20;
		$template = $this->get_template(true);
		$query = '';
		$exact_result = array();
		$manu = array();
		$skip = 0;
		$limit = 20;
		if(isset($_GET['needle']))
		{
			$yousers = self::search_yousers($_GET['needle']);
			if($yousers !== null)
			{
				$template->assign('yousers', $yousers);
			}
			$query = self::build_search_query($_GET['needle']);
			$Sphinx = new SphinxClient();
			$Sphinx->SetLimits($skip, $limit);
			$Sphinx->SetMatchMode(SPH_MATCH_BOOLEAN);
			$exact_result = $Sphinx->Query($query);
			
			if(isset($exact_result['matches']))
			{
				$tmp = self::match_results($exact_result['matches']);
				$exact_result['matches'] = $tmp['devices'];
				$exact_manu = $tmp['manu'];
				$template->assign('exact_manu', $exact_manu);
				$template->assign('exact_result', $exact_result);
			}
			else
			{
				$exact_result['matches'] = array();
				$template->assign('exact_result', $exact_result);
			}
			if(!isset($exact_result['total']) || $exact_result['total'] < 1)
			{
				$Sphinx = new SphinxClient();
				$Sphinx->SetLimits($skip, $limit);
				$Sphinx->SetMatchMode(SPH_MATCH_ANY);
				$common_result = $Sphinx->Query($query);
				if(isset($common_result['matches']))
				{
					$tmp = self::match_results($common_result['matches'], $exact_result['matches']);
					$common_result['matches'] = $tmp['devices'];
					$common_manu = $tmp['manu'];
					$common_result['total'] -= $tmp['count'];
					$template->assign('common_result', $common_result);
					$template->assign('common_manu', $common_manu);
				}
			}
			$template->assign('needle', $_GET['needle']);
		}
	}
	
	private function search_yousers($needle)
	{
		$needle = explode(' ', $needle);
		$search = "'%";
		$search .= implode("%' OR nickname LIKE '%", $needle);
		$search .= "%'";
		$query = "SELECT youser_id FROM yousers WHERE nickname LIKE $search;";
		$yousers = DBManager::Get()->query("SELECT youser_id FROM yousers WHERE nickname LIKE $search ORDER BY nickname;")->to_array('youser_id', 'youser_id');
		return $yousers;

	}
	
	private function match_results($result, $exclude = array())
	{
		$count = 0;
		$manu = array();
		foreach($result as $id => $na)
		{
			if(!isset($exclude[$id]))
			{
				$result[$id] = array('device_id' => DBManager::Get('devices')->query("SELECT device_id FROM device WHERE device_id_int = ?", $id)->fetch_item());
			}
			else
			{
				$count++;
				unset($result[$id]);
			}
			$manu[$na['attrs']['manufacturer_id']] = DBManager::Get('devices')->query("SELECT manufacturer_id FROM manufacturer WHERE manufacturer_id_int = ?", $na['attrs']['manufacturer_id'])->fetch_item();
		}
		return array('devices' => $result, 'manu' => $manu, 'count' => $count);
	}
	
	private function build_search_query($string)
	{
		$search = explode(' ', $string);
		if(is_array($search) && !empty($search))
		{
			$and_search = array();
			$or_search = array();
			foreach ($search as $needle)
			{
				$tmp = BabelFish::ReverseLookup($needle, '', true);
				if(count($tmp) > 2)
				{
					foreach ($tmp as $line)
					{
						$tmp2[$line['phrase_id']] = $line['phrase_id'];
					}
					$or_search[] = $tmp2;
					unset($tmp2);
				}
				else if(!empty($tmp))
				{
					$and_search[] = reset($tmp);
				}
				else $or_search[] = array($needle);
			}
			$query = '';
			if(!empty($and_search))
			{
				$query .= '(';
				foreach ($and_search as $key => $search)
				{
					if($key >= 1)
					{
						$query .= ' &';
					}
					$query .= ' *';
					$query .= implode('* & *', $search);
					$query .='*';
				}
				$query .= ')';
			}
			if(!empty($or_search))
			{
				if(!empty($and_search))
				{
					$query .= ' & ';
				}
				foreach ($or_search as $key => $search)
				{
					if($key >= 1)
					{
						$query .= ' & ';
					}
					$query .= ' (*';
					$query .= 	implode('* | *', $search);
					$query .='*)';
				}
			}
		}
		return $query;
	}
	
	public function build_index()
	{
		$status = DBManager::Get('devices')->query("SELECT status FROM services WHERE service_name = 'search';")->fetch_item();
		DBManager::Get('devices')->query("TRUNCATE search_index;");
		if($status == 1){
			$time = DBManager::Get('devices')->query("SELECT timestamp FROM services WHERE service_name = 'search';")->fetch_item();
			$date= date('d-m-Y').' '.date('H:i:s')-('0:30:0');
			echo $time."\r\n";
			echo $date."\r\n";
			if ($time < $date){
				echo 'passt';
				DBManager::Get('devices')->query("UPDATE services SET status = 0 WHERE service_name ='search'");
				$status = 0;
			}
		}
		if($status != 1)
		{
			DBManager::Get('devices')->query("UPDATE services SET status = 1 WHERE service_name = 'search';");
		
			$devices = build_fulltext::get_devices();
			foreach ($devices as $device)
			{
				$data[$device['device_id_int']] = array();
				$tmp = build_fulltext::get_device_indications($device['device_id']);
				$indication = '';
				if($tmp !== false && $tmp != null)
				{
					$indication = implode(' ',$tmp);
					unset($tmp);
				}
				$os = '';
				$tmp = build_fulltext::get_operating_systems($device['device_id']);
				if($tmp !== false && $tmp != null)
				{
					$os =  implode(' ', $tmp);
					unset($tmp);
				}
				$components = '';
				$tmp = build_fulltext::get_device_components($device['device_id']);
				if($tmp !== false && $tmp != null)
				{
					$components = implode(' ', $tmp);
					unset($tmp);
				}
				$tmp = build_fulltext::get_colors($device['device_id']);
				$colors = '';
				if($tmp !== false && $tmp != null)
				{
					$colors = implode(' ', $tmp);
					unset($tmp);
				}
				$build_form = '';
				$tmp = build_fulltext::get_buildform($device['device_id']);
				if($tmp !== false && $tmp != null)
				{
					$build_form =implode(' ', $tmp);
					unset($tmp);
				}
				$input_method = '';
				$tmp = build_fulltext::get_inputmethod($device['device_id']);
				if($tmp !== false && $tmp != null)
				{
					$components .= strtolower(' '.implode(' ', $tmp));
					unset($tmp);
				}
				$tmp = build_fulltext::get_releaseyear($device['device_id']);
				$release_year = '';
				if($tmp !== false && $tmp != null)
				{
					$release_year = $tmp;
					unset($tmp);
				}
				$device_names = '';
				$tmp = build_fulltext::get_device_translations($device['device_names_name']);
				if($tmp !== false && $tmp != null)
				{
					$device['device_names_name'] .= ' '.strtolower(' '.implode(' ', $tmp));
					unset($tmp);
				}
				DBManager::Get('devices')->query("LOCK TABLES search_index WRITE;");
				DBManager::Get('devices')->query("INSERT INTO search_index (device_id_int, device_name, manufacturer_name, manufacturer_id, components, colors, build_form, operatingsystem, indication, release_year) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE components=VALUES(components);",$device['device_id_int'], $device['device_names_name'], $device['manufacturer_name'], $device['manufacturer_id_int'], $components, $colors, $build_form, $os, $indication, $release_year);
				DBManager::Get('devices')->query("UNLOCK TABLES;");
			}
			DBManager::Get('devices')->query("UPDATE services SET status = 0 WHERE service_name ='search'");
		}
		//FrontController::Relocate('Index');
	}
}
?>
