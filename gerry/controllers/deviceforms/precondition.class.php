<?

class precondition extends Controller
{
	private $replace = array(' ', ',', '.', '(', ')', '\'', '\"', '§', '$', '%', '&', '/', '=', '?', '`', '´', '-', '+', '*', '#');
	
	public function Index()
	{
		$template = $this->get_template(true);
		$investigator = new investigator();
		$tablenames = $investigator->getAllPreconditionTables();
		array_push($tablenames, 'manufacturer', 'language', 'continent', 'country');
		$tablenames = $this->sort($tablenames);
		$count = $this->count_preconditions($tablenames);
		$template->assign('count', $count);
		$template->assign('tables', $tablenames);
	}
	
	private function sort($tablenames)
	{
		$lang = BabelFish::GetLanguage();
		foreach ($tablenames as $name)
		{
			$new[BabelFish::Get(strtoupper($name), $lang)] = strtoupper($name);
		}
		ksort($new);
		return $new;
	}
	
	public function presetlist()
	{
		$template = $this->get_template(true);
		if(isset($_GET['device_id']))
		{
			$device_id = $_GET['device_id'];
			$template->assign('device_id', $device_id);
		}
		if(isset($_GET['tab']))
		{
			$tab = $_GET['tab'];
			$template->assign('tab', $tab);
		}
		if(isset($_GET['table']))
		{
			$table = $_GET['table'];
			$presets = investigator::get_preconditions_from_table(strtolower($table));
			if(is_array($presets) && !empty($presets))
			{
				$presets = $this->sort($presets);
			}
			$template->assign('table', $table);
			$template->assign('presets', $presets);
			if(isset($_GET['device_id']))
			{
				$device_id = $_GET['device_id'];
				$template->assign('device_id', $device_id);
			}
			if(isset($_GET['tab']))
			{
				$tab = $_GET['tab'];
				$template->assign('tab', $tab);
			}
		}
		else FrontController::Relocate('Index');
	}
	
	public function presetdetails()
	{
		$template = $this->get_template(true);
		if(isset($_GET['device_id']))
		{
			$device_id = $_GET['device_id'];
			$template->assign('device_id', $device_id);
		}
		if(isset($_GET['tab']))
		{
			$tab = $_GET['tab'];
			$template->assign('tab', $tab);
		}
		if(isset($_GET['table']))
		{
			$table = strtolower($_GET['table']);
			if(isset($_GET['add']))
			{
				$template->assign('add', $_GET['add']);
			}
			else
			{
				$template->assign('add', false);
			}
			$template->assign('table', $table);
			if($table == 'processor_type')
			{
				$manufacturers = investigator::getManufacturers();
				$template->assign('manufacturers', $manufacturers);
			}
			else if($table == 'manufacturer')
			{
				$manufacturers = investigator::getManufacturers();
				$template->assign('manufacturers', $manufacturers);
				$countries = investigator::get_countries();
				$template->assign('countries', $countries);
			}
			else if($table == 'country')
			{
				$continents = investigator::get_continents();
				$template->assign('continents', $continents);
			}
			if(isset($_GET['detail']))
			{
				$detail = $_GET['detail'];
				$template->assign('specific', $detail);
				$details = investigator::get_preset_details($table, strtoupper($detail));
				if(is_array($details) && !empty($details))
				{
					foreach ($details as $detail)
					{
						$keys = array_keys($detail);
					}
				}
				else
				{
					$object = new $table();
					$keys = $object->__toArray();
					$keys = array_keys($keys);
				}
				$template->assign('keys', $keys);			
				$template->assign('details', $details);
			}
			else FrontController::Relocate('Index');
		}
		else FrontController::Relocate('Index');
	}
	
	public function addpreset()
	{
		if(Youser::Is('god', 'root', 'administrator'))
		{
			$template = $this->get_template(true);
			if(isset($_GET['device_id']))
			{
				$device_id = $_GET['device_id'];
				$template->assign('device_id', $device_id);
			}
			if(isset($_GET['tab']))
			{
				$tab = $_GET['tab'];
				$template->assign('tab', $tab);
			}
			if(isset($_GET['table']))
			{
				$table = strtolower($_GET['table']);
				if(isset($_GET['add']))
				{
					$template->assign('add', $_GET['add']);
				}
				else
				{
					$template->assign('add', false);
				}
				$template->assign('table', $table);
				if($table == 'processor_type')
				{
					$manufacturers = investigator::getManufacturers();
					$template->assign('manufacturers', $manufacturers);
				}
				else if($table == 'manufacturer')
				{
					$manufacturers = investigator::getManufacturers();
					$template->assign('manufacturers', $manufacturers);
					$countries = investigator::get_countries();
					$template->assign('countries', $countries);
				}
				else if($table == 'country')
				{
					$continents = investigator::get_continents();
					$template->assign('continents', $continents);
				}
				if(isset($_GET['detail']))
				{
					$detail = $_GET['detail'];
					$template->assign('specific', $detail);
					$details = investigator::get_preset_details($table, strtoupper($detail));
					if(is_array($details) && !empty($details))
					{
						foreach ($details as $detail)
						{
							$keys = array_keys($detail);
						}
					}
					else
					{
						$object = new $table();
						$keys = $object->__toArray();
						$keys = array_keys($keys);
					}
					$template->assign('keys', $keys);			
					$template->assign('details', $details);
				}
				else FrontController::Relocate('Index');
			}
			else FrontController::Relocate('Index');
		}
		else return false;
	}

	
	private function count_preconditions($tablenames)
	{
		$db = DBManager::Get('devices');
		foreach($tablenames as $table)
		{
			$result = $db->query("SELECT COUNT(*) FROM ".strtolower($table));
			if(!$result->is_empty())
			{
				while($data = $result->fetch_array())
				{
					$count[$table] = $data['COUNT(*)'];
				}
			}
			else $count[$table] = 0;
		}
		$result->release();
		return $count;
	}
	
	
	public function deletepreset()
	{
		if(Youser::Is('god', 'root', 'administrator'))
		{
			$device_id = null;
			$tab = null;
			if(isset($_GET['device_id']))
			{
				$device_id = $_GET['device_id'];
			}
			if(isset($_GET['tab']))
			{
				$tab = $_GET['tab'];
			}
			if(isset($_GET['table']))
			{
				$table = $_GET['table'];
				if(isset($_GET['id']))
				{
					$id = $_GET['id'];
					$table_id = $table.'_id';
					DBManager::Get('devices')->query("DELETE FROM $table WHERE $table_id = ?", $id);
					FrontController::Relocate('presetlist', array('table' => $table, 'device_id' => $device_id, 'tab' => $tab));
				}
				else FrontController::Relocate('Index');
			}
			else FrontController::Relocate('Index');
		}
		else return false;
	}
	
	public function updatepreset()
	{
		if(Youser::Is('god', 'root', 'administrator'))
		{
			$device_id = null;
			$tab = null;
			if(isset($_GET['device_id']))
			{
				$device_id = $_GET['device_id'];
			}
			if(isset($_GET['tab']))
			{
				$tab = $_GET['tab'];
			}
			if(isset($_POST['table']))
			{
				$table = $_POST['table'];
				$object = new $table();
				foreach ($_POST as $key => $value)
				{
					$object->$key = self::clean_presets($value);
				}
				$object->save();
				FrontController::Relocate('presetlist', array('table' => $table, 'device_id' => $device_id, 'tab' => $tab));
			}
			else FrontController::Relocate('Index');
		}
		else return false;
	}
	
	public function savepreset()
	{
		if(Youser::Is('god', 'root', 'administrator'))
		{
			$device_id = null;
			$tab = null;
			if(isset($_POST['device_id']))
			{
				$device_id = $_POST['device_id'];
			}
			if(isset($_POST['tab']))
			{
				$tab = $_POST['tab'];
			}
			if(isset($_POST['table']))
			{
				$table = $_POST['table'];
				$table_id = $table.'_id';
				$object = new $table();
				$object->$table_id = md5(uniqid(time(true)));
				foreach ($_POST as $key => $value)
				{
					if (!empty($value) && strpos(strtoupper($table), strtoupper($value)) == false && $value != $tab && $value != $device_id)
					{
						if(strpos($key, '_id') == false && !empty($value) && !is_numeric($value))
						{
							if($table == 'manufacturer')
							{
								$object->$key = self::clean_presets($value, 'MANU_');
							}
							else
							{
								$object->$key = self::clean_presets($value);
							}
						}
						else 
						{
							$object->$key = $value;
						}
					}
				}
				$object->youser_id = Youser::Id();
				$object->timestamp = 'NOW()';
				$object->save();
				FrontController::Relocate('presetlist', array('table' => $table, 'device_id' => $device_id, 'tab' => $tab));
			}
			else FrontController::Relocate('Index');
		}
		else return false;
	}
	
	private static function clean_presets($preset, $pre = '')
	{
		$clean_preset = $pre.''.strtoupper(str_replace(array(' ', '-', '.', '/', ':'), '_', $preset));
		/*
		if(BabelFish::Get($clean_preset) == $clean_preset)
		{
			BabelFish::InsertPhrase(BabelFish::GetLanguage(), $clean_preset, $preset, Youser::Id());
		}*/
		return strtoupper($clean_preset);
	}
}

?>