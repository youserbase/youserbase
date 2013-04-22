<?php

class phoneConfig extends sheetConfig 
{
	private static $data;
	private static $compare = false;
	private static $preset;
	
	public static function startDataSheetBuilding($data, $tab, $device_id, $compare = false, $skip = 0, $presets = null)
	{
		Timer::Report('phoneConfig: sheetbuilder start');
		if($compare == 'compare')
		{
			self::$compare = true;
		}
		if($presets !== null)
		{
			self::$preset = $presets;
		}
		self::$data = $data;
		$sheet = sheetConfig::get_sheet($device_id);
		$sheet = self::setupDataSheet($sheet, $tab, $device_id, $skip);
		Timer::Report('phoneConfig: sheetbuilder done');
		return $sheet;
	}
	
	
	public static function setupDataSheet($build_array, $tab, $device_id, $skip = 0)
	{
		if(isset($tab))
		{
			Timer::Report('phoneConfig: sheetbuilder between');
			foreach($build_array as $tabtemp => $tabdata)
			{
				Timer::Report('phoneConfig: sheetbuilder ignoring'.$tabtemp);
				$sheet[$tabtemp] = $tabtemp;
			}
			if(isset($build_array[$tab]))
			{
				$sheet[$tab] = self::buildtab($build_array[$tab], $device_id, $skip);
				
			}
		}
		return $sheet;
	}
	
	private static function buildtab($tabdata, $device_id, $skip = 0)
	{
		
		foreach($tabdata as $component => $table)
		{
			if(!is_array($table))
			{
				$sheet[$table] = self::buildTable($table, $device_id, $skip);
			}
			else
			{
				$exists = false;
				foreach ($table as $line)
				{
					if(class_exists($line))
					{
						$exists = true;
						$sheet[$component][] = self::buildTable($line, $device_id, $skip);
					}
				}
				if(!$exists)
				{
					$tmp[$component] = self::buildTable($component, $device_id, $skip);
					foreach ($tmp[$component] as $key => $value)
					{
						if(in_array($key, $table))
							$sheet[$component][$key] = $value;
						else if($key == 'timestamp' || $key == 'youser_id')
						{
							$sheet[$component][$key] = $value;
						}
					}
				}
			}
		}
		return $sheet;
	}
	
	public function buildTable($table, $device_id, $skip, $edit = false)
	{
		if(class_exists($table))
		{
			$component_id = DBManager::Get('devices')->query("SELECT component_id FROM device_components WHERE table_name = ? AND device_id =?;", $table, $device_id)->fetch_item();
			$component = call_user_func_array(array($table, 'Get'), array());
			if(!$edit)
			{
				$data = $component->Loadview($component_id, $skip);
			}
			else
			{
				$data = $component->Loadedit($component_id, $skip);
			}
			return $data;
		}
		
	}
	
	private static function decideinput($input, $frame, $device_id)
	{
		
		if(is_array($input))
		{
			$sheet = self::buildarrayinput($input, $frame, $device_id);
		}
		else
		{
			$sheet[$input] = self::buildinput($input, $device_id);
		}
		return $sheet;
	}
	
	private static function buildinput($input, $device_id)
	{
		if(class_exists($input))
		{
			$object = new $input();
			$fields = $object->toArray();
			if(isset(self::$data[$input.'_type']))
			{
				foreach($fields as $field => $value)
				{
					if(is_array(self::$data[$input.'_type']))
					{
						foreach(self::$data[$input.'_type'] as $key => $value)
						{
							$object->$key = $value;
						}
					}
				}
			}
			$object_data = $object->__toArray();
			
			$sheet = sheetBuilder::buildForm($fields, '', $object_data, $input, $device_id, self::$compare, self::$preset);
			return $sheet;
		}
		return false;
	}
	
	private static function buildarrayinput($input, $frame, $device_id)
	{
		
		$sheet = array();
		if(class_exists($frame))
		{
			$object = new $frame();
			$fields = $object->toArray();
			if(isset(self::$data[$frame.'_type']))
			{
				foreach($fields as $field => $value)
				{
					if(is_array(self::$data[$frame.'_type']))
					{
						foreach(self::$data[$frame.'_type'] as $key => $value)
						{
							$object->$key = $value;
						}
					}
				}
			}
			$object_data = $object->__toArray();
			$tmp = sheetBuilder::buildForm($fields, '', $object_data, $frame, $device_id, self::$compare, self::$preset);
			foreach($input as $input_name => $input_field)
			{
				if(is_array($input_field))
				{
					$array = '';
					$tmp_label = '<label for="'.$input_name.'"><phrase id='.$input_name.'/></label>';
					$tmp_input = '';
					foreach($input_field as $input => $fields)
					{
						if(isset($tmp[$fields]['input']))
						{
							if(!is_array($fields))
							{
								 $tmp_input[] = $tmp[$fields]['input'];
							}
						}
					}
					$input_tmp = implode('*', $tmp_input);
					$sheet[$input_name][$input_name] = array('label' => $tmp_label, 'input' => $input_tmp);
				}
				else
				{
					if(!class_exists($input_field))
					{
						if(isset($tmp[$input_field]['label']))
						{
							$sheet[$input_field]['label'] = array('label' =>$tmp[$input_field]['label']);
						}
						if(isset($tmp[$input_field]['input']))
						{
							$sheet[$input_field]['input'] = array('input'  => $tmp[$input_field]['input']);
						}
						
					}
					else
					{
						$sub_object = new $input_field();
						$fields = $sub_object->toArray();
						if(isset(self::$data[$input_field.'_type']))
						{
							foreach($fields as $field => $value)
							{
								if(is_array(self::$data[$input_field.'_type']))
								{
									foreach(self::$data[$input_field.'_type'] as $key => $value)
									{
										$sub_object->$key = $value;
									}
								}
							}
						}
						$object_data = $sub_object->__toArray();
						$sheet[$input_field] = sheetBuilder::buildForm($fields, '', $object_data, $input_field, $device_id, self::$compare, self::$preset);
					}
				}
			}
		}
		else
		{
			foreach($input as $form)
			{
				$sheet[$form] = self::buildinput($form, $device_id);
			}
		}
		return $sheet;
	}
	
}
?>