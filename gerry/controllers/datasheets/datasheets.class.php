<?php
class datasheets extends Controller
{
	private $preconditionTables;
	private $device_id;
	private $components;
	private $multimedia_classes;
	private $gps_classes;
	private $device_types;
	private $preconditions;
	private $tablefields;
	private $main_type;
	private $device_name;
	private $manufacturer_name;

	public function getDeviceComponents($device_type)
	{
		return DBManager::Get('devices')->query("SELECT table_name FROM device_design WHERE device_type=?",
			$device_type
		)->to_array(null, 'table_name');
	}

	/**
	 * Controls the creation of the initial DatasheetForm which
	 * lets the youser specify the device_types of a new device
	 *
	 */
	public function buildStartSheet()
	{
		$builder = new initialSheetBuilder();
		$initialForm = $builder->initSheet();

		$template = $this->get_template(true);
		if(isset($_GET['device_name']))
		{
			$template->assign('device_name', $_GET['device_name']);
		}
		$template->assign('initialForm', $initialForm);
	}

	public function page()
	{
		Timer::Report('Datasheet: Wakeup');
		if (isset($_GET['device_id']))
		{
			$device_id = $_GET['device_id'];
		}
		if (isset($_GET['device_id_int']))
		{
			$device_id_int = $_GET['device_id_int'];
		}
		else
		{
			$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
		}

		Timer::Report('Datasheet: Event before');
		Event::Dispatch('alert', 'DataSheet:Display', $device_id);
		Timer::Report('Datasheet: Event after');

		$this->device_id = $device_id;
		Timer::Report('Datasheet: Before fetch');
		$device_data = investigator::getDeviceInformation($device_id);
		Timer::Report('Datasheet: After fetch');
		$this->main_type = DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id = ?;", $device_id)->fetch_item();
		$this->device_name = $device_data['device_name'];
		$this->manufacturer_name = $device_data['manufacturer_name'];
		if (isset($_GET['main_type']))
		if (isset($_GET['main_type']))
		{
			$this->main_type = $_GET['main_type'];
		}
		if (isset($_GET['copy_failure']))
		{
			Dobber::ReportError('PROMPT_CHANGE_NAME');
		}

		Timer::Report('Datasheet: Before head data');
		$sheet = sheetConfig::get_sheet($device_id);
		//$device_data = $this->getHeadData($this->device_id);
		Timer::Report('Datasheet: After head data');
		$tab = 'common';
		if(isset($_GET['tab']))
		{
			$tab = $_GET['tab'];
		}
		$requests = device_stats::update_stats($device_id, $device_id_int, $tab);

		$numbers = array(
			'Comments' => DBManager::Get('devices')->query("SELECT COUNT(comments_id) FROM comments WHERE device_id=?", $device_id)->fetch_item(),
			'Media' => DBManager::Get()->query("SELECT COUNT(*) FROM device_media WHERE device_id=?", $device_id)->fetch_item(),
		);

		/*
		if(isset($_GET['tab']))
		{
			FrontController::Relocate(FrontController::GetHost().'/'.BabelFish::GetLanguage().'/Mobile/'.str_replace(' ','_',BabelFish::Get(reset($device_data['manufacturer_name']))).'/'.str_replace(' ','_',BabelFish::Get(reset($device_data['device_name']))).'#'.$_GET['tab']);
		}
		
	*/
		$template = $this->get_template(true);
		$template->assign('sheet', $sheet);
		$template->assign('numbers', $numbers);
		$template->assign('explicit_manufacturer', reset($device_data['manufacturer_name']));
		$template->assign('explicit_model', reset($device_data['device_name']));
		$template->assign('device_id', $this->device_id);
		$template->assign('tab', $tab);
	}

	public function phonesheet($device_id = null, $tab = null)
	{
		Timer::Report('Phonesheet: Wakeup');
		$skip = 0;
		$tab = isset($_REQUEST['tab'])
			? $_REQUEST['tab']
			: 'common';
		if ($device_id === null and isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
		}
		$this->device_id = $device_id;

		$device_data = investigator::getDeviceInformation($device_id);

		$this->main_type = DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id = ?;", $device_id)->fetch_item();
		$this->device_name = $device_data['device_name'];
		$this->manufacturer_name = $device_data['manufacturer_name'];
		$manufacturer_id = investigator::getManufacturerIdByName($this->manufacturer_name[0]);

		Timer::Report('Phonesheet: sheetbuilder call');

		$sheet = sheetConfig::get_sheet($device_id);
		$sheet = phoneConfig::startDataSheetBuilding($sheet, $tab, $this->device_id, 'single', $skip);

		$build_in = investigator::get_build_in($device_id);
		$device_data = Device::Get($device_id);
		if (!$device_data)
		{
			throw new Exception('No device id');
		}

		$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
		//device_stats::update_stats($device_id, $device_id_int, $tab);

	
		$template = $this->get_template(true);
		$comment_count = DBManager::Get('devices')->query("SELECT category, COUNT(category) AS amount FROM comments WHERE device_id = ? AND offensive_counts <= 2 GROUP BY category", $device_id)->to_array('category', 'amount');
		$template->assign('comment_count', $comment_count);
		$template->assign( compact('tab', 'sheet') );
		$template->assign('model', $this->device_name);
		$template->assign('manufacturer_id', $manufacturer_id);
		$template->assign('manufacturer_name', $this->manufacturer_name);
		$template->assign('device_name', $this->device_name);
		$template->assign('main_type', $this->main_type);
		$template->assign('device_id', $this->device_id);
		$template->assign('build_in', $build_in);
		$template->assign('skip', 0);

		$template->assign('device_manufacturer', BabelFish::Get($this->manufacturer_name[0]));
		$template->assign('device_model', BabelFish::Get($this->device_name[0]));
	}



	public function viewcomponent()
	{
		$template = $this->get_template(true);
		$skip = 0;
		if(isset($_REQUEST['skip']))
		{
			$skip = $_REQUEST['skip'];
		}
		elseif(isset($_REQUEST['amp;skip']))
		{
			$skip = $_REQUEST['amp;skip'];
		}
		if(isset($_REQUEST['amp;tab']))
		{
			$tab = $_REQUEST['amp;tab'];
		}
		elseif(isset($_REQUEST['tab']))
		{
			$tab = $_REQUEST['tab'];
		}
		if(isset($_REQUEST['amp;table']))
		{
			$table = $_REQUEST['amp;table'];
		}
		elseif(isset($_REQUEST['table']))
		{
			$table = $_REQUEST['table'];
		}
		if(isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
		}
		if(isset($_REQUEST['amp;tbodies']))
		{
			$tbodies = $_REQUEST['amp;tbodies'];
		}
		elseif(isset($_REQUEST['tbodies']))
		{
			$tbodies = $_REQUEST['tbodies'];
		}
		if(isset($_REQUEST['timestamp']))
		{
			self::update();
		}
		$rating = getratings::rating($device_id, $tab);
		$sheet = sheetConfig::get_sheet($device_id);
		$sheet = phoneConfig::startDataSheetBuilding($sheet, $tab, $device_id, 'single', $skip);
		$trows = 0;
		$build_in = investigator::get_build_in($device_id);
		$template->assign('skip', $skip);
		$template->assign('trows', $trows);
		$template->assign('contents', $sheet[$tab][$table]);
		$template->assign('table', $table);
		$template->assign('tab', $tab);
		$template->assign('device_id', $device_id);
		$template->assign('tbodies', $tbodies);
		$template->assign('rating', $rating[$device_id]);
		$template->assign('build_in', $build_in);
	}

	public function register()
	{
		$template = $this->get_template(true);
		$template->assign('return_to', $_REQUEST['return_to']);
	}

	public function editcomponent()
	{
		$template = $this->get_template(true);
		$skip = 0;
		if(isset($_REQUEST['skip']))
		{
			$skip = $_REQUEST['skip'];
		}
		if(isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
		}
		$device_data = investigator::getDeviceInformation($device_id);
		$sheet = sheetConfig::get_sheet($device_id);
		$admin = false;
		if(isset($_REQUEST['table']))
		{
			$table = $_REQUEST['table'];
		}
		if(isset($_REQUEST['tab']))
		{
			$tab = $_REQUEST['tab'];
		}
		else
		{
			$admin = true;
			foreach ($sheet as $tab_name => $table_name)
			{
				if($table_name == $table)
					$tab = $tab_name;
			}
		}

		if(isset($sheet[$tab][$table]))
		{
			$tables = $sheet[$tab][$table];

			if(is_array($tables))
			{
				foreach ($tables as $sub_table)
				{
					if(class_exists($sub_table))
					{
						$data[] = phoneConfig::buildTable($sub_table, $device_id, $skip, true);
					}
					else $data = phoneConfig::buildTable($table, $device_id, $skip, true);
				}
			}
			else
			{
				$data = phoneConfig::buildTable($tables, $device_id, $skip, true);
			}
		}
		else
		{
			$data = phoneConfig::buildTable($table, $device_id, $skip, true);
		}
		$template->assign('skip', $skip);
		$template->assign('component_data', $data);
		$template->assign('table', $table);
		$template->assign('admin', $admin);
		$template->assign('tab', $tab);
		$template->assign('device_id', $device_id);
		$tbodies = 0;
		if(isset($_REQUEST['tbodies']))
		{
			$tbodies = $_REQUEST['tbodies'];
		}
		else if(isset($_REQUEST['amp;tbodies']))
		{
			$tbodies = $_REQUEST['amp;tbodies'];
		}
		$template->assign('tbodies', $tbodies);
		$return_to = '';
		if(isset($_REQUEST['return_to']))
		{
			$return_to = $_REQUEST['return_to'];
		}
		$template->assign('return_to', $return_to);
	}

	public function update()
	{
		if(isset($_REQUEST['table']))
		{
			$table = $_REQUEST['table'];
		}
		if(isset($_REQUEST['timestamp']))
		{
			DBManager::Get('devices')->query("UPDATE $table SET timestamp = NOW() WHERE timestamp = ?;", $_REQUEST['timestamp']);
		}
		return;
	}
/*
	public function datasheet($sheet)
	{
		$template = $this->create_template('datasheet');
		$template->assign('sheet', $sheet);
		$template->assign('manufacturer_name', $this->manufacturer_name);
		$template->assign('device_name', $this->device_name);
		$template->assign('main_type', $this->main_type);
		$template->assign('device_id', $this->device_id);
		$template = $template->render();
		return $template;
	}
*/
/*
	private function getHeadData($device_id)
	{
		$device_information = investigator::getDeviceInformation($device_id);
		if($tables = investigator::getTablesByID($device_id))
		{
			$this->components = investigator::getComponentsByID($tables);
		}
		return $device_information;
	}
*/
	public function savesheet()
	{
		$saved = saveSheet::Save();
		if($saved !== false)
		{
			Dobber::ReportSuccess('<phrase id="%s"/> <phrase id="SAVED"/>', strtoupper($saved));
		}
		Lightbox::Close();
		FrontController::Relocate($_REQUEST['return_to']);
	}

	public function savedevice()
	{
		if(isset($_GET['manufacturer_name']) && isset($_GET['device_name']))
		{
			$device_name = $_GET['device_name'];
			$manufacturer = $_GET['manufacturer_name'];
			$device_id = investigator::getInfoForDeviceByName($_GET['manufacturer_name'],$_GET['device_name']);
		}

		else
		{
			FrontController::Relocate('buildStartSheet');
		}

		if($device_id == NULL)
		{

			$device_id = saveSheet::saveDevice();
			if($device_id == NULL)
			{
				FrontController::Relocate('buildStartSheet', array('device_name' => $device_name));
			}
		}
		FrontController::Relocate('page', array('device_id' => $device_id));
	}

	public function deletedevice()
	{
		if(!Youser::Is('god', 'root', 'administrator'))
		{
			return false;
		}
		if(isset($_GET['device_id']))
		{
			deleteDevice::StartRemoval($_GET['device_id']);
			FrontController::Relocate('buildStartSheet');
		}
		else
		{
			deleteDevice::StartRemoval();
			FrontController::Relocate('classgenerator', 'generator', 'initGenerator', array('deleted' => 'success'));
		}
	}

	public function pictureGallery()
	{
		if(isset($_GET['device_id']))
		{
			savePicture::save($_GET['device_id']);
		}
		FrontController::Relocate('page', array('device_id' => $_GET['device_id']));
	}

	public function sync()
	{
		if(isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
			$template = $this->get_template(true);
			$template->assign('device_id', $device_id);
			$manufacturers = DBManager::Get('devices')->query("SELECT m.manufacturer_name, d.manufacturer_id FROM device_names AS d LEFT JOIN manufacturer AS m ON d.manufacturer_id = m.manufacturer_id;")->to_array('manufacturer_id', 'manufacturer_name');
			$template->assign('manufacturers', $manufacturers);
		}
		else if(isset($_REQUEST['source_device_id']) && isset($_REQUEST['target_device_id']))
		{
			syncdevices::get($_REQUEST['source_device_id'], $_REQUEST['target_device_id']);
			FrontController::Relocate('page', array('device_id' => $_REQUEST['target_device_id']));
		}
	}

	public function sync_devices()
	{
		$template = $this->get_template(true);
		$devices = false;
		if(isset($_REQUEST['manufacturer_id']))
		{
			$manufacturer_id = $_REQUEST['manufacturer_id'];
			$devices = DBManager::Get('devices')->query("SELECT device_id, device_names_name FROM device_names WHERE manufacturer_id = ?;", $manufacturer_id)->to_array('device_id', 'device_names_name');
		}
		$template->assign('devices', $devices);
	}

	public function initCopyDevice()
	{
		$template = $this->get_template(true);
		$device_id = $_GET['device_id'];
		$template->assign('device_id', $device_id);
		$device_information = investigator::getDeviceInformation($device_id);
		$manufacturer_name = $device_information['manufacturer_name'][0];
		$template->assign('model', $_GET['model']);
		$template->assign('manufacturer_name', $manufacturer_name);
		$template->assign('main_type', $_GET['main_type']);

		$manufacturers = investigator::getManufacturers();
		$template->assign('manufacturers', $manufacturers);
	}

	public function copyDevice()
	{
		$device_id = $_POST['device_id'];
		$device_name = $_POST['device_name'];
		$device_type = $_POST['device_type'];
		$manufacturer_id = $_POST['manufacturer_id'];
		if(empty($_POST['new_device_name']))
		{
			Dobber::ReportError('ENTER_DEVICE_NAME');
			FrontController::Relocate('page', array('device_id' => $device_id));
		}
		$new_device_name = $_POST['new_device_name'];
		if($device_name != $new_device_name)
		{
			$new_device_id = copydevice::copy($new_device_name, $manufacturer_id, $device_type, $device_id);
			Dobber::ReportSuccess('SUCCESS_DEVICE_CREATED', array('device'=>$new_device_name));
			FrontController::Relocate('page', array('device_id' => $new_device_id));
		}
		else
		{
			Dobber::ReportError('ERROR_DEVICE_ALREADY_EXISTS');
			FrontController::Relocate('page', array('device_id' => $device_id));
		}
	}
/*
	public function save_disclaimer()
	{
		disclaimer_text::set_disclaimer();
		FrontController::Relocate('page', array('device_id' => $_REQUEST['device_id'], 'tab' => $_REQUEST['tab']));
	}
*/
}
?>
