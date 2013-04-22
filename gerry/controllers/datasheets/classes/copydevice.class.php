<?php
class copydevice extends saveSheet
{
	private static $device_id_tables = array('device', 'device_names', 'device_device_types');
	
	public static function copy($new_name, $new_manufacturer, $device_type, $device_id)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$name = 'DEVICE_'.strtoupper(str_replace(array(' ', '-', '.'), '_', $new_name));
		if(investigator::getdeviceid($name, $new_manufacturer) !== null)
		{
			Dobber::ReportError('Gerät mit identischem Namen und Hersteller bereits vorhanden');
			FrontController::Relocate('page', array('device_id' => $device_id));
		}
		if(BabelFish::Get($name) == $name)
		{
			BabelFish::InsertPhrase('uk', $name, $new_name, $youser_id);
		}
		$new_device_id = md5(uniqid($new_name.$new_manufacturer.time(true)));

		$tables = investigator::getTablesByID($device_id);
		$components = investigator::getComponentsByID($tables);
		$device_id_int = self::save_device($new_device_id, $name);
	
		if(is_array($components))
		{
			self::copy_components($components, $new_device_id, $device_id_int);
		}
		self::new_device_names($new_device_id, $device_id_int, $device_type, $name, $new_manufacturer);
		self::new_device_device_types($new_device_id, $device_id_int, $device_type);
		self::create_ancestors($new_device_id, $device_id, $_POST['relation']);
		self::copy_buildin($new_device_id, $device_id_int, $device_id);
		

		//computesimilarity::compute_similarity();
		Event::Dispatch('alert', 'Device:Created', $youser_id, $new_device_id);
		return $new_device_id;
	}
	
	private static function copy_components($components, $device_id, $device_id_int)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$confirmed = 'no';
		foreach ($components as $table_name => $table_data)
		{
			$table_name = str_replace('_type', '', $table_name);
			$table_id_field = $table_name.'_id';
			$component_id = md5(uniqid($table_name.time(true)));
			$component_id_int = rand();
			$object = new $table_name();
			if(isset($table_data[$table_id_field]))
			{
				foreach ($table_data[$table_id_field] as $pos => $id)
				{
					$table_id = md5(uniqid($table_name.time(true)));
					foreach ($table_data as $key => $value)
					{
						if(isset($value[$pos]))
						{
							$object->$key = $value[$pos];
						}
					}
					$object->component_id = $component_id;
					$object->component_id_int = $component_id_int;
					$object->$table_id_field = $table_id;
					$object->youser_id = $youser_id;
					$object->save();
				}
				$device_components = new device_components();
				$device_components->device_component_id = md5(uniqid($table_name.time(true)));
				$device_components->component_id = $component_id;
				$device_components->component_id_int = $component_id_int;
				$device_components->device_id = $device_id;
				$device_components->device_id_int = $device_id_int;
				$device_components->table_name = $table_name;
				$device_components->timestamp = 'NOW';
				$device_components->youser_id = $youser_id;
				if(Youser::Id('administrator', 'root', 'god'))
				{
					$confirmed = 'yes';
				}
				$device_components->confirmed = $confirmed;
				$device_components->save();
			}
		}
	}
	
	private static function new_device_names($device_id, $device_id_int, $device_type, $device_name, $manufacturer_id)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$device_names = new device_names();
		$device_names->device_names_id = uniqid($device_id.$device_type.time(true));
		$device_names->device_id = $device_id;
		$device_names->device_id_int = $device_id_int;
		$device_names->manufacturer_id = $manufacturer_id;
		$device_names->device_names_name = $device_name;
		$device_names->timestamp = 'NOW()';
		$device_names->youser_id = $youser_id;
		$device_names->save();
	}
	
	private static function new_device_device_types($device_id, $device_id_int, $device_type)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$device_device_types = new device_device_types();
		$device_device_types->ddt_id = md5(uniqid($device_id.time(true)));
		$device_device_types->device_id = $device_id;
		$device_device_types->device_id_int = $device_id_int;
		$device_device_types->device_type_name = $device_type;
		$device_device_types->main_type = 'yes';
		$device_device_types->timestamp = 'NOW()';
		$device_device_types->youser_id = $youser_id;
		$device_device_types->save();
	}
	
	public static function create_ancestors($device_id, $old_device_id, $relationship)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		if($relationship == 1)
		{
			$predecessor = new predecessors();
			$predecessor->parent_id = $old_device_id;
			$predecessor->child_id = $device_id;
			$predecessor->timestamp = 'NOW()';
			$predecessor->youser_id = $youser_id;
			$predecessor->save();
		}
		else if($relationship == 2)
		{
			$predecessor = new predecessors();
			$predecessor->parent_id = $device_id;
			$predecessor->child_id = $old_device_id;
			$predecessor->timestamp = 'NOW()';
			$predecessor->youser_id = $youser_id;
			$predecessor->save();
		}
		else if($relationship == 3)
		{
			$siblings = new siblings();
			$siblings->brother_id = $device_id;
			$siblings->sister_id = $old_device_id;
			$siblings->timestamp = 'NOW()';
			$siblings->youser_id = $youser_id;
			$siblings->save();
		}
	}
	
	public static function copy_buildin($device_id, $device_id_int, $old_device_id)
	{
		$youser_id = 107;
		if(Youser::Id())
		{
			$youser_id = Youser::Id();
		}
		$build_in = DBManager::Get('devices')->query("SELECT component_name, build_in_status FROM build_in WHERE device_id = ?;", $old_device_id)->to_array('component_name', 'build_in_status');
		if(empty($build_in))
		{
			return false;
		}
		foreach ($build_in as $component_name => $status)
		{
			$object = new build_in();
			$object->device_id = $device_id;
			$object->device_id_int = $device_id_int;
			$object->component_name = $component_name;
			$object->build_in_status = $status;
			$object->youser_id = $youser_id;
			$object->timestamp = 'NOW()';
			$object->save();
		}
	}
}
?>