<?php
class System_Search extends Controller 
{
	public function Index()
	{
		$template = $this->get_template(true);
		if(isset($_REQUEST['needle']))
		{
		$template->assign('needle', $_REQUEST['needle']);
		}
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
		if(isset($_REQUEST['page']))
		{
			$skip = intval($_REQUEST['page']*$limit);
		}
		if(isset($_GET['needle']))
		{
			$yousers = self::search_yousers($_GET['needle']);
			$youser_count = 0;
			if($yousers !== null)
			{
				$template->assign('yousers', $yousers);
				$youser_count = count($yousers);
			}
			$template->assign('youser_count', $youser_count);
			$query = self::build_search_query($_GET['needle']);
			$Sphinx = new SphinxClient();
			$Sphinx->SetMatchMode(SPH_MATCH_BOOLEAN);
			$Sphinx->SetLimits($skip, $limit);
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
				$Sphinx->SetMatchMode(SPH_MATCH_ANY);
				$Sphinx->SetLimits($skip, $limit);
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
			$template->assign('skip', $skip);
			$template->assign('limit', $limit);
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
}
?>