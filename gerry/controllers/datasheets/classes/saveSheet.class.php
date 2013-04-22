<?php
class saveSheet extends phoneConfig
{
	public static function Save()
	{
		if(!isset($_REQUEST['changed']))
		{
			return false;
		}
		if(isset($_REQUEST['tab']) && isset($_REQUEST['table']) && isset($_REQUEST['device_id']))
		{
			$confirmed = 'no';
			if(Youser::Is('root', 'administrator', 'god'))
			{
				$confirmed = 'yes';
			}
			$tables = array();
			$tables[] =  $_REQUEST['table'];
			$sheet = sheetConfig::get_sheet($_REQUEST['device_id']);
			if(isset($sheet[$_REQUEST['tab']][$_REQUEST['table']]))
			{
				$tables = $sheet[$_REQUEST['tab']][$_REQUEST['table']];
			}
			if(!is_array($tables))
			{
				$tables = array($tables);
			}
			$component_id = md5(uniqid(time(true)));
			$component_id_int = uniqid(time(true));
			$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $_REQUEST['device_id'])->fetch_item();
			if($device_id_int == null)
			{
				$device_id_int = rand();
			}
			$youser_id = 107;
			if(Youser::Id())
			{
				$youser_id = Youser::Id();
			}
			$build_in = self::save_buildin($_REQUEST['device_id'], $device_id_int, $_REQUEST['table'], $youser_id);
			if($build_in === 'no')
			{
				Event::Dispatch('alert', 'Device:Edited', $youser_id, $_REQUEST['device_id']); 
				return $_REQUEST['table'];
			}
			foreach ($tables as $table_name)
			{
				if(Youser::Is('god')){
				Dobber::ReportNotice($table_name);
			}
				if(!class_exists($table_name))
				{
					$table_name = $_REQUEST['table'];
				}
				$object = call_user_func_array(array($table_name, 'Get'), array());
				$data = $object->Loadedit();
				$multiple = array();
				foreach($data[$table_name]['description'] as $line_name => $line_type)
				{
					if($line_type == 'hidden')
					{
						if(isset($_REQUEST[$table_name.'_'.$line_name]))
						{
							if(empty($_REQUEST[$table_name.'_'.$line_name]))
							{
								if(strpos($line_name, 'id_int'))
								{
									if($line_name == 'component_id_int')
									{
										$object->component_id_int = $component_id_int;
									}
									else
									{
										$_REQUEST[$table_name.'_'.$line_name] = uniqid(time(true));
									}
								}
								else
								{
									if($line_name == 'component_id')
									{
										$object->component_id = $component_id;
										
									}
									else
									{
										$_REQUEST[$table_name.'_'.$line_name] = md5(uniqid(time(true)));
									}
								}
							}
							else
							{
								if($line_name == 'component_id_int')
								{
									$component_id_int = $_REQUEST[$table_name.'_'.$line_name];
								}
								if($line_name == 'component_id')
								{
									$component_id = $_REQUEST[$table_name.'_'.$line_name];
								}
								$object->$line_name = $_REQUEST[$table_name.'_'.$line_name];
							}
						}
					}
					else if($line_type == 'multiple' && isset($_REQUEST[$line_name]))
					{
						foreach ($_REQUEST[$line_name] as $value)
						{
							$multiple[] = array('name' => $line_name, 'value' => self::clean($value));
						}
					}
					else if($line_name != 'timestamp')
					{
						if(isset($_REQUEST[$line_name]))
							$object->$line_name = self::clean($_REQUEST[$line_name]);
						else $object->$line_name = null;
					}
				}
				if(empty($multiple))
				{
					self::save_component($object, $_REQUEST['device_id'], $device_id_int, $table_name, $component_id, $component_id_int, $confirmed, $youser_id);
				}
				else
				{
					foreach ($multiple as $multi)
					{
						self::save_component($object, $_REQUEST['device_id'], $device_id_int, $table_name, $component_id, $component_id_int, $confirmed, $youser_id, $multi);
					}
				}
				if(Youser::Is('root', 'administrator', 'god'))
				{
					YouserCredits::Reward(Youser::Id(), 5, 'scruffy', 'edit');
				}
				self::save_device_component($_REQUEST['device_id'], $device_id_int, $table_name, $component_id, $component_id_int, $confirmed, $youser_id);
				Event::Dispatch('alert', 'Device:Edited', $youser_id, $_REQUEST['device_id']); 
			}
			return $_REQUEST['table'];
		}
		return false;
	}
	
	public function save_buildin($device_id, $device_id_int, $component, $youser_id)
	{
		if(isset($_REQUEST['build_in']))
		{
			DBManager::Get('devices')->query("DELETE FROM build_in WHERE device_id = ? AND component_name = ?;", $device_id, $component);
			$object = call_user_func_array(array('build_in', 'Get'), array());

			$object->device_id = $device_id;
			$object->device_id_int = $device_id_int;
			$object->component_name = $component;
			$object->build_in_status = self::clean($_REQUEST['build_in']);
			$object->youser_id = $youser_id;
			$object->save();
		}
		return $_REQUEST['build_in'];
	}
	
	public function save_component($object, $device_id, $device_id_int, $table_name, $component_id, $component_id_int, $confirmed, $youser_id, $multi = null)
	{
		$id = $table_name.'_id';
		$id_int = $table_name.'_id_int';
		$object->$id = md5(uniqid(time(true)));
		$object->$id_int = uniqid(time(true));
		if($multi !== null)
		{
			$object->$multi['name'] = self::clean($multi['value']);
		}
		if($table_name == 'market_information')
		{
			$object->country_id = BabelFish::GetLanguage();
		}
		$object->youser_id = $youser_id;
		$affected = $object->save();
		return $affected;
	}
	
	public function save_device_component($device_id, $device_id_int, $table_name, $component_id, $component_id_int, $confirmed, $youser_id)
	{
		$dc = new device_components();
		$dc->device_component_id = md5(uniqid(time(true)));
		$dc->device_id = $device_id;
		$dc->device_id_int = $device_id_int;
		$dc->component_id = $component_id;
		$dc->component_id_int = $component_id_int;
		$dc->table_name = $table_name;
		$dc->confirmed = $confirmed;
		$dc->timestamp = 'NOW()';
		$dc->youser_id = $youser_id;
		$dc->save();
	}

	 /** Cleaning function for submitted values
	 *
	 * @param string $dirty
	 * @param  $anum boolean Defines wether the value is alphanumeric or not. Standard false
	 * @return cleaned value
	 */
	public static function clean($dirty, $anum=false)
	{

		if (is_array($dirty))
		{
			foreach ($dirty as $key => $value)
			{
				$clean[$key] = self::clean($value);
			}
		}
		else
		{
			$clean = htmlentities($dirty);
			if(strpos($clean, ',') !== false)
			{
				$clean = floatval(str_replace(',', '.', $clean));
			}
			if(is_numeric($clean))
				$clean = abs($clean);
		}
			return $clean;
	}

	public static function saveDevice()
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
			$replace = array(' ', '-');
			$device_type = '';
			$device_name = '';
			$manufacturer_name = '';
			$ean = '';
			$youser_id = $youser_id;
			$timestamp = 'NOW()';
			$device_id = md5(uniqid($_REQUEST['device_name'].time(true)));
			if (isset($_REQUEST['main_type'])){
				$device_type = $_REQUEST['main_type'];
			}
			if (isset($_REQUEST['device_name'])){
				$device_name = 'DEVICE_'.strtoupper(str_replace($replace, '_', self::clean($_REQUEST['device_name'])));
				if(BabelFish::Get($device_name, BabelFish::GetLanguage()) == $device_name){
					BabelFish::InsertPhrase('uk', $device_name, self::clean($_REQUEST['device_name']), Youser::Id());
				}
			}
			if (isset($_REQUEST['manufacturer_name'])){
				$manufacturer_name = self::clean($_REQUEST['manufacturer_name']);
			}
			if (isset($_REQUEST['EAN'])){
				$ean = self::clean($_REQUEST['EAN']);
			}
			if (isset($_REQUEST['confirmed']) && Youser::Is('administrator', 'root', 'god')){
				$confirmed = $_REQUEST['confirmed'];
			}if (isset($_REQUEST['device_id'])){
				$device_id = $_REQUEST['device_id'];
			}
			$device_id_int = self::save_device($device_id, $device_name);
			$db = DBManager::Get('devices');
			$result = $db->query("SELECT manufacturer_id FROM manufacturer WHERE manufacturer_name LIKE ?;", $manufacturer_name);
			if(!$result->is_empty()){
				while ($data = $result->fetch_array()){
					$manufacturer_id = $data['manufacturer_id'];
				}
			}
			if(is_array($device_type)){
				foreach ($device_type as $type){
					self::saveDeviceDeviceType($device_id, $device_id_int, $type);
				}
			}
			else self::saveDeviceDeviceType($device_id, $device_id_int, $device_type);
			$device_names_id = self::saveDeviceName($device_id, $device_id_int, $device_name, $manufacturer_id);
			
			Event::Dispatch('alert', 'Device:Created', Youser::Id(), $device_id);
			computesimilarity::compute_similarity();
			return $device_id;
	}

	protected static function save_device($device_id, $device_name)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$confirmed = 'no';
		$device = new device();
		$device->device_id = $device_id;
		if(Youser::Is('administrator', 'root', 'god'))
		{
			YouserCredits::Reward($youser_id, 10, 'scruffy', 'edit');
			$confirmed = 'yes';
		}
		$device->confirmed = $confirmed;
		$device->device_name = $device_name;
		
		$device->youser_id = $youser_id;
		$device->save();
		$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
		return $device_id_int;
	}

	protected static function saveDeviceName($device_id, $device_id_int, $device_name, $manufacturer_id)
	{
		if($device_id_int == null)
		{
			$device_id_int = rand();
		}
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$device_names_id = md5(uniqid($device_name.$manufacturer_id.time(true)));
		$device_names = new device_names();
		$device_names->device_names_id = $device_names_id;
		$device_names->device_id = $device_id;
		$device_names->device_id_int = $device_id_int;
		$device_names->manufacturer_id =  $manufacturer_id;
		$device_names->device_names_name = $device_name;
		$device_names->youser_id = $youser_id;
		$device_names->save();
	}

	protected static function saveDeviceDeviceType($device_id, $device_id_int, $device_type)
	{
		if($device_id_int == null)
		{
			$device_id_int = rand();
		}
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$device_device_types = new device_device_types();
		$ddtid = md5(uniqid($device_type.time(true)));
		$device_device_types->ddt_id = $ddtid;
		$device_device_types->device_id = $device_id;
		$device_device_types->device_id_int = $device_id_int;
		$device_device_types->device_type_name = $device_type;
		$device_device_types->main_type = 'yes';
		$device_device_types->youser_id = $youser_id;
		$device_device_types->save();
	}

	protected static function saveDeviceType($device_id)
	{
		$device_types = self::clean($_REQUEST['device_type_name']);
		if(is_array($device_types))
		{
			foreach ($device_types as $device_type)
			{
				$ddtid = md5(uniqid($device_type.time(true)));
				$device_device_type = new device_device_types($ddtid, $device_id, $device_type, 'no', 'NOW()', Youser::Id());
				$device_device_type->save();
			}
		}
		else
		{
			$ddtid = md5(uniqid($device_types.time(true)));
			$device_device_type = new device_device_types($ddtid, $device_id, $device_types, 'no');
			$device_device_type->save();
		}
	}

	protected static function saveObject($table, $device_id)
	{
		if($device_id_int == null)
		{
			$device_id_int = rand();
		}
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$table = str_replace('_type', '', self::clean($table));
		$confirmed = 'no';
		$table_id = $table.'_id';
		if(isset($_REQUEST['confirmed']))
		{
			$confirmed = $_REQUEST['confirmed'];
		}
		else if(Youser::Is('administrator', 'god', 'root'))
		{
			$confirmed = 'yes';
		}
		$table_id = $table.'_id';
		if(!isset($_REQUEST[$table_id]) || empty($_REQUEST[$table_id]))
		{
			$object_id = md5(uniqid($table.$table_id.time(true)));
		}
		else
		{
			$object_id = self::clean($_REQUEST[$table_id], true);
		}
		$component_id = md5(uniqid($_REQUEST['table'].time(true)));
		$component_id_int =rand();
		
		$dcid = md5(uniqid($table_id.time(true)));
		$device_components = new device_components();
		$device_components->device_component_id = $dcid;
		$device_components->device_id = $device_id;
		$device_components->component_id = $component_id;
		$device_components->component_id_int = $component_id_int;
		$device_components->table_name = $table;
		$device_components->timestamp = 'NOW()';
		$device_components->youser_id = $youser_id;
		$device_components->save();
		self::updateObject($table, $component_id, $device_id);
	}

	protected static function updateObject($table, $component_id, $device_id)
	{
		$object = new $table();
		$db = dbManager::Get('devices');
		$result = $db->query("SELECT * FROM $table WHERE component_id = ? AND timestamp = (SELECT MAX(timestamp) FROM $table WHERE component_id = ?);", $component_id, $component_id);

		if(!$result->is_empty())
		{
			while($data = $result->fetch_array())
			{
				foreach ($data as $key => $value)
				{
					if(is_numeric($value)) $value = abs($value);
					if(!isset($object->$key))
						$object->$key = self::clean($value);
					else
					{
						$object->$key = array($object->$key);
						$object->$key = array_push($object->$key, self::clean($value));
					}
				}
			}
		}
		$table_fields = array_keys($object->__toArray());
		$multiple = array();
		foreach ($table_fields as $field)
		{
			if(isset($_REQUEST[$field]) && !empty($_REQUEST[$field]))
			{
				if(!is_array($_REQUEST[$field]))
				{
					$object->$field = self::clean($_REQUEST[$field]);
				}
				else
				{
					$id_field = $table.'_id';
					foreach ($_REQUEST[$field] as $entry)
					{
						$multiple[] = array('field' => $field, 'value' => self::clean($entry));
					}
				}
			}
		}
		$id_field = $table.'_id';
		$object->component_id = $component_id;
		$object->country_id = BabelFish::GetLanguage();
		if(isset($_REQUEST['currency_type_id']))
		{
			$currency = $_REQUEST['currency_type_id'];
			$object->currency_type_id = $currency[0];
		}
		$object->timestamp = 'NOW()';
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$object->youser_id = $youser_id;
		if(!empty($multiple))
		{
			foreach ($multiple as $line)
			{
				$object->$id_field = md5(uniqid($id_field.time(true)));
				$object->$line['field'] = $line['value'];
				$object->save();
			}
		}
		else
		{
			$object->$id_field = md5(uniqid($id_field.time(true)));
			$object->save();
		}
		if(Youser::Is('administrator', 'god', 'root'))
		{
			DBManager::Get('devices')->query("UPDATE device_components SET confirmed = 'yes', youser_id = ?, timestamp = NOW()  WHERE device_id = ? and table_name = ?;",Youser::Id(), $device_id, $table);
		}
		else
		{
			DBManager::Get('devices')->query("UPDATE device_components SET confirmed = 'no' WHERE device_id = ? and table_name = ?;", $device_id, $table);
		}
	}

	protected static function removeBeforeInsert($tablename, $component_id)
	{
		DBManager::Get('devices')->query("DELETE FROM {$tablename} WHERE component_id=?", $component_id);
	}

	protected static function saveObjectData($table, $component_id, $component_id_int, $device_id)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$object_id = md5(uniqid($device_id.$component_id.time(true)));
		$object_id_field = $table.'_id';
		$object = new $table();
		$object->component_id = $component_id;
		$object->component_id_int = $component_id_int;
		$object->$object_id_field = $object_id;
		$fields = $object->toArray();
		$timestamp = 'NOW()';
		foreach ($fields as $field)
		{
			if(isset($_REQUEST[$field['field']]) && !empty($_REQUEST[$field['field']]))
			{
				if(is_array($_REQUEST[$field['field']]))
				{
					foreach ($_REQUEST[$field['field']] as $entry)
					{
						$multiple[self::clean($entry)] = self::clean($field['field']);
					}
				}
			}
		}
		if(isset($multiple))
		{
			$object_array = array();
			foreach($multiple as $value => $field_name)
			{
				$object_id = md5(uniqid($table.$component_id.time(true)));
				$object = new $table($object_id, $component_id);
				foreach ($fields as $field)
				{
					if($field['field'] != $field_name && $field['field'] != $table.'_id'  && $field['field'] != 'component_id' && isset($_REQUEST[$field['field']])  && !empty($_REQUEST[$field['field']]))
					{
						$object->$field['field'] = self::clean($_REQUEST[$field['field']]);
					}
				}
				$object->component_id = $component_id;
				$object->component_id_int = $component_id_int;
				$object->$field_name = $value;
				$object->timestamp = $timestamp;
				$youser_id = 107;
				if(Youser::Id())
				{
					$youser_id = Youser::Id();
				}
				$object->youser_id = $youser_id;
				$object->country_id = BabelFish::GetLanguage();
				if(isset($_POST['currency_type_id']))
				{
					$currency = $_POST['currency_type_id'];
					$object->currency_type_id = $currency[0];
				}
				$object_array[] = $object;
			}
			$object_array = array_unique($object_array);
			foreach($object_array as $object)
			{
				$object->save();
			}
		}
		else
		{
			foreach ($fields as $field)
			{
				if($field['field'] != $table.'_id'  && $field['field'] != 'component_id' && isset($_REQUEST[$field['field']]))
				{
					if(isset($_REQUEST[$field['field']]))
					{
						$object->$field['field'] = self::clean($_REQUEST[$field['field']]);
					}
				}
			}
			$object->component_id = $component_id;
			$object->component_id_int = $component_id_int;
			$object->timestamp = $timestamp;
			$youser_id = 107;
			if(Youser::Id())
			{
				$youser_id = Youser::Id();
			}
			$object->youser_id = $youser_id;
			$object->country_id = BabelFish::GetLanguage();
			if(isset($_POST['currency_type_id']))
			{
				$currency = $_POST['currency_type_id'];
				$object->currency_type_id = $currency[0];
			}
			$object->save();
		}
		if(Youser::Is('administrator', 'god', 'root'))
		{
			
			DBManager::Get('devices')->query("UPDATE device_components SET confirmed = 'yes', youser_id = ?, timestamp = NOW()  WHERE device_id = ? and table_name = ?;",Youser::Id(), $device_id, $table);
		}
		else
		{
			DBManager::Get('devices')->query("UPDATE device_components SET confirmed = 'no' WHERE device_id = ? and table_name = ?;", $device_id, $table);
		}
	}

	private static function update_device($device_id)
	{
		DBManager::Get('devices')->query("UPDATE device SET timestamp = 'NOW' WHERE device_id = ?;", $device_id);
	}
}
?>