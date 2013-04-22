<?php
class admin extends Controller
{
	private $id;
	private $mother_id;

	public function setupForms()
	{
		$template = $this->getTemplate(true);
		$adminForm = new AdminForm();
		$types = array('components' => 'Komponente', 'equipment' => 'Zubeh�r', 'functions' => 'Funktion');
		$template->assign('types', $types);
		$selected = '';
		if(isset($_POST['type']))
		{
			$tables = $adminForm->initForm($_POST['type']);
			$selected = $_POST['type'];
			$template->assign('selectedForm', $selected);
			$template->assign('tables', $tables);
		}
		if(isset($_POST['form']))
		{
			$tables = $adminForm->initForm(null, $_POST['form']);

			$selected = $_POST['type'];
			$selectedForm = $_POST['form'];
			$template->assign('selectedForm', $selectedForm);
			$template->assign('showTable', $tables);
		}
		$template->assign('selected', $selected);
	}

	public function safe()
	{
		if(isset($_POST['table']))
		{
			$object = new $_POST['table'];
			$motherObject = new $_POST['type']();
			switch ($_POST['type'])
			{
				case 'components':
					$typeid = 'component_id';
					$type_name = 'components_name';
					break;
				case 'functions':
					$typeid = 'function_id';
					$type_name = 'function_name';
					break;
				case 'equipment':
					$typeid = 'equipment_id';
					$type_name = 'equipment_name';
					break;
			}
			$primary = $this->getPrimarykey($object);
			$this->id = md5(uniqid($_POST['table'], true)."/".time());
			$this->mother_id = md5(uniqid($_POST['table'].'_'.$_POST['type'], true)."/".time());
			$object->__set($primary, $this->id);
			$object->__set($typeid, $this->mother_id);
			$motherObject->__set($typeid, $this->mother_id);
			$motherObject->__set($type_name, $_POST['table']);
			foreach($_POST as $key => $post)
			{
				if($key != $type_name)
				$motherObject->__set($key, $post);
				$object->__set($key, $post);
			}
			$db = DBManager::Get('devices');
			//$db->query("INSERT INTO whois (whois_id, table_name) VALUES(?,?) ON DUPLICATE KEY UPDATE whois_id=VALUES(whois_id), table_name=VALUES(table_name) ", $this->mother_id, $_POST['table']);
			$object->save();
			$motherObject->save();

			$adminForm = new AdminForm();
			$tables = $adminForm->initForm(null, $_POST['table'], $object->__toArray(), $motherObject->__toArray());
			$template = $this->getTemplate(true);
			$template->assign('showTable', $tables);
			$template->assign('object', $object->__toArray());
			$template->assign('motherObject', $motherObject->__toArray());
		}
	}

	public function alterComponents()
	{
		$template = $this->getTemplate(true);
		$alterComponents = new alterComponents();
		$content = '';
		if(isset($_POST['components']))
		{
			$content = $alterComponents->alterComponent($template);
		}
		else if (isset($_POST['functions']))
		{
			$content = $alterComponents->alterFunctions($template);
			
		}
		else if (isset($_POST['equipment']))
		{
			$content = $alterComponents->alterEquipment($template
			);
		}
		else if(isset($_POST['alter']))
		{
			
			$content = array('content' => 'Daten �ndern');
		}
		
		$template->assign('content', $content);
	}
	
	private function getPrimarykey($object)
	{
		$object = new $object();
		$attribs = $object->toArray();
		foreach($attribs as $key => $attrib)
		{
			if(strpos($attrib, 'PRI') !== false)
				return $key;
		}
	}

	public function showStats()
	{
		$template = $this->getTemplate(true);
		$getComponents = new getComponents();
		$components = $getComponents->getAllComponents();
		$template->assign('components', $components);
		$functions = $getComponents->getAllFunctions();
		$template->assign('functions', $functions);
		$equipment = $getComponents->getAllEquipment();
		$template->assign('equipment', $equipment);
		$manu = new deviceinformation();
		$manufacturers = $manu->getAllManufacturers();
		$template->assign('manufacturers', $manufacturers);
	}

	public function designDevices()
	{
		$template = $this->getTemplate(true);
		$devices = new deviceinformation();
		$device_types = $devices->getAllDeviceTypes();
		$template->assign('device_types', $device_types);
		$devices = new designDevices();
		if(isset($_POST['newDevice']))
		{
			if($_POST['newDevice'] == 'show')
			{
				$adminForm = new AdminForm();
				$tables = $adminForm->getAllTables();
				$classes = $adminForm->getDeviceTypeClasses();
				$template->assign('classes', $classes);
				$template->assign('tables', $tables);
			}
			else if($_POST['newDevice'] == 'set')
			{
				$devices->setDeviceDefinition();
			}
			else if ($_POST['newDevice'] == 'describe')
			{
				$components = $devices->getAllComponentsFromDeviceType();
				$template->assign('components', $components);
			}
		}
	}
	
	public function manufacturer()
	{
		$template = $this->getTemplate(true);
		$get = new getManufacturer();
		$countries = $get->getCountries();
		$template->assign('countries', $countries);
		$def = new TableDefinition();
		$manufacturer = $def->getTableDefinition('manufacturer');
		$template->assign('manufacturer', $manufacturer);
		if(isset($_POST['setManufacturer']))
		{
			if ($_POST['setManufacturer'] == 'valuesSet')
			{
				$id = md5(uniqid($_POST['manufacturer_name']).time());
				$country_id = $_POST['country'];
				$manufacturer_name = $_POST['manufacturer_name'];
				$manufacturer_website = $_POST['maunfacturer_website'];
				$manufacturer = new manufacturer($id, $country_id, $manufacturer_name, $manufacturer_website);
				$manufacturer->save();
			}
		}
	}
}
?>