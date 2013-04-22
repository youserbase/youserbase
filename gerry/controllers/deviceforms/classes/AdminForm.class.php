<?php
class AdminForm
{
	public function initForm($type='', $table='', $object='', $motherObject='')
	{
		if(($tableNames = $this->getAllTables()) !== false)
		{
			$tableTypes = $this->assignTableType($tableNames);
		}
		$form = '';
		foreach($tableTypes as $tables => $types)
		{

			if(strpos($types, 'device') === false && $types == $type && $tables != $type)
			{
				$form[$tables][] = $this->buildForm($tables, $types, $object, $motherObject);
			}
			else if($table == $tables)
			{
				$form[$table][] = $this->buildForm($table, $types, $object,$motherObject);
			}
		}
		return $form;
	}

	public function getAllTables()
	{
		$tableNames = array();
		$folder = dirname(__FILE__).'/components/';
		$tableNames = glob($folder.'*.class.php');
		$parameters = array_fill(0, count($tableNames), '.class.php');
		$tableNames = array_map('basename', $tableNames, $parameters);
		return $tableNames;
	}
	
	public function getDeviceTypeClasses()
	{
		$db = DBManager::Get('devices');
		$result = $db->query('SELECT device_type_class FROM device_type_classes');
		if(!$result->is_empty())
		{
			while ($data = $result->fetch_array())
			{
				foreach ($data as $value)
				{
					$classes[] = $value;
				}
			}
			$result->release();
			return $classes;
		}
		return false;
		
	}

	public function assignTableType($tableNames)
	{
		$tableTypes = array();
		foreach($tableNames as $table)
		{
			$object = new $table();
			$objectAttribs = $object->toArray();
			if(array_key_exists('component_id', $objectAttribs) && !array_key_exists('device_id', $objectAttribs))
			{
				$tableTypes[$table] = 'components';
			}
			else if(array_key_exists('equipment_id', $objectAttribs) && !array_key_exists('device_id', $objectAttribs))
			{
				$tableTypes[$table] = 'equipment';
			}
			else if(array_key_exists('function_id', $objectAttribs) && !array_key_exists('device_id', $objectAttribs))
			{
				$tableTypes[$table] = 'functions';
			}
			else if(array_key_exists('device_id', $objectAttribs) && array_key_exists('component_id', $objectAttribs))
			{
				$tableTypes[$table] = 'device_components';
			}
			else if(array_key_exists('device_id', $objectAttribs) && array_key_exists('equipment_id', $objectAttribs))
			{
				$tableTypes[$table] = 'device_equipment';
			}
			else if(array_key_exists('device_id', $objectAttribs) && array_key_exists('function_id', $objectAttribs))
			{
				$tableTypes[$table] = 'device_functions';
			}

		}
		return $tableTypes;
	}

	private function buildForm($table, $type, $data=null, $motherData=null)
	{
		$attribs = array();

		$object = new $table();
		$attribs = $object->toArray();
		
		$formBuilder = new formBuilder();
		$form = '';
		$value = '';
		foreach($attribs as $input => $var)
		{
			if(strpos($input, '_id')===false)
			{
				if($data != null)
					$value = $data[$input];			
				$form[$input] = $formBuilder->textField($input, $value);
			}
		}
		if($type != null)
		{
			$motherObject = new $type;
			$motherAttribs = $motherObject->toArray();
			foreach($motherAttribs as $input => $var)
			{
				if(strpos($input, '_id')===false)
				{
					if($motherData != null)
						$value = $motherData[$input];	
					$form[$input] = $formBuilder->textField($input, $value);
				}
			}
		}		
		return $form;
	}
}
?>