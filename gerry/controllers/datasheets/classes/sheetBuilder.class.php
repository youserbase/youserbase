<?php
class sheetBuilder
{
	private static $device_id;
	private static $compare;
	private static $preset;
	/**
	 * Returns a form based filled with teh parameters given in
	 *
	 * @param array $precon containing preconditions for select-fields
	 * @param array $fields containing the fields of the form to build
	 * @param array $rating containig the rating of fields
	 * @param array $object_data containing the values of the object-fields
	 * @param string $table the name of the Form
	 * @return array containig the form
	 */
	public static function buildForm($fields, $rating ='', $object_data = '', $table, $device_id, $compare, $preset = null)
	{
		self::$preset = $preset;
		self::$compare = $compare;
		self::$device_id = $device_id;
		foreach($fields as $field => $settings)
		{
			if($field == 'accessories_type_id')
			{
				//print_r($object_data).die();
			}
			$input[$settings['field']]['id'] = $field;
			$class = '';
			$start = strpos($settings['type'], '(');
			$length = substr($settings['type'],$start);
			$length = str_replace(')', '', $length);
			$length = str_replace('(', '', $length);
			if($length > 50)
				$length = 50;
			if(strpos($settings['type'], 'int') !== false || strpos($settings['type'], 'float') !== false)
			{
				$class = 'number';
			}
			if(strpos($settings['null'], 'NO') !== false)
			{
				$class .= ' required';
			}
			switch ($settings['input'])
			{
				case 'date':
					$input[$settings['field']] = self::buildDate($settings, $object_data[$field], $length, $table);
					break;
				case 'text':
					$input[$settings['field']] = self::buildText($settings, $object_data[$field], $class, $length, $table, '', $object_data);
					break;
				case 'hidden':
					if($field != $table.'_id')
						$input[$settings['field']] = self::buildHidden($settings, $object_data[$field], $table);
					break;
				case 'select':
					$input[$settings['field']] = self::buildSelect('', $object_data[$field], $field, $table, $settings['field']);
					break;
				case 'multiple':
					$multiple = 'multiple="multiple"';
					$input[$settings['field']] = self::buildSelect($multiple, $object_data[$field], $field, $table, $settings['field'].'[]');
					break;
				case 'decide':
					$input[$settings['field']] = self::build_decide('', $object_data[$field], $field, $table, $settings['field']);
			}
		}
		return $input;
	}

	private static function buildHidden($settings, $data, $table)
	{
		if(is_array($data))
		{
			$data = array_pop($data);
		}
		$input['hidden'] = "<input type='hidden' name='".$table."_".$settings['field']."' value='".$data."'/>";
		return $input;
	}


	private static function buildDate($settings, $data, $length, $table)
	{
		$class = ' ';
		$emptyvalue = '<phrase id="UNKNOWN" quiet="true"/>';
		if(is_array($data))
			$data = array_pop($data);
		if(!empty($data))
			$emptyvalue = $data;
		$input['label'] = strtoupper($settings['field']);
		$input['input']['form'] = "<input type='text' name='".$settings['field']."' value='".$data."'  size=".$length." class='add_datepicker $class' />";
		$input['input']['text'] = '<div class="'.$class.'"><ul class="date"><li>';
		$input['input']['text'] .= $emptyvalue;
		$input['input']['text'] .= '</li></ul></div>';
		return $input;
	}

	private static function get_preset($table)
	{
		if(strpos($table, '_type') != false)
		{
			$table = str_replace(array('type_id', 'type_name'), 'type', $table);
		}
		else
		{
			$table = str_replace(array('_id', '_name'), '', $table);
		}
		$object = new $table;
		$object->Get();
		$preset[$table] = $object->Load();
		return $preset;
	}

	private static function buildText($settings, $data, $class, $length, $table, $precon, $complete_data)
	{
		if($table == 'phone' || $table == 'contacts' || $table == 'contacts_functions' || $table == 'caller_protocoll'|| $table == 'phone_function')
		{
			$table = 'contact_management';
		}
		else if($table == 'themes' || $table == 'ringtone_format' || $table == 'background_picture' || $table =='screensaver' || $table == 'profiles')
		{
			$table = 'personalization';
		}

		$class = '';
		$emptyvalue = '<phrase id="UNKNOWN" quiet="true"/>';
		if(is_array($data))
			$data = array_pop($data);
		if(!empty($data))
		{
			if($data !== 0 || $data !== 0.00)
			{
				$emptyvalue = $data;
			}
		}
		$tmp = '';
		$input['label'] = strtoupper($settings['field']);
		$input['input']['form'] = "<input type='text' class='$class' name='".$settings['field']."' value='".$data."'  size='".$length."'/>";
		if(strpos($settings['field'], 'price') !== false)
		{
			$precon = self::get_preset('currency_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="currency_type_id">';
			foreach ($precon['currency_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['currency_type_id'].'"';
				if (isset($complete_data['currency_type_id'][0]))
				{
					if($preset['currency_type_id'] == $complete_data['currency_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['currency_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['currency_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else if((strpos($settings['field'], 'memory') !== false) || (strpos($settings['field'], 'ram') !== false) || in_array($settings['field'], array('mms_max_size', 'max_size_off_attachement')))
		{
			$precon = self::get_preset('memory_size_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="memory_size_type_id">';
			foreach ($precon['memory_size_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['memory_size_type_id'].'"';
				if (isset($complete_data['memory_size_type_id'][0]))
				{
					if($preset['memory_size_type_id'] == $complete_data['memory_size_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['memory_size_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['memory_size_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else if(in_array($settings['field'], array('body_length', 'body_width', 'body_thickness', 'display_size_diagonally', 'projection_min_size_diagonally', 'projection_max_size_diagonally', 'flash_reach', 'lens_focal_length', 'camera_macro_from', 'camera_macro_to')))
		{
			$precon = self::get_preset('size_units_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="size_units_type_id">';
			foreach ($precon['size_units_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['size_units_type_id'].'"';
				if(isset($complete_data['size_units_type_id'][0]))
				{
					if($preset['size_units_type_id'] == $complete_data['size_units_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['size_units_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['size_units_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else if(in_array($settings['field'], array('body_weight')))
		{
			$precon = self::get_preset('weight_units_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="weight_units_type_id">';
			foreach ($precon['weight_units_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['weight_units_type_id'].'"';
				if(isset($complete_data['weight_units_type_id'][0]))
				{
					if($preset['weight_units_type_id'] == $complete_data['weight_units_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['weight_units_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['weight_units_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else if(in_array($settings['field'], array('talktime', 'standbytime', 'battery_runtime_audio_playback', 'battery_runtime_audio_recording', 'battery_runtime_video_playback', 'battery_runtime_video_recording', 'shutter_time', 'delay_release', 'shutter_speed', 'battery_charging_time')))
		{
			$precon = self::get_preset('time_units_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="time_units_type_id">';
			foreach ($precon['time_units_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['time_units_type_id'].'"';
				if(isset($complete_data['time_units_type_id'][0]))
				{
					if($preset['time_units_type_id'] == $complete_data['time_units_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['time_units_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['time_units_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else if($settings['field'] == 'power_output')
		{
			$precon = self::get_preset('power_output_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="power_output_type_id">';
			foreach ($precon['power_output_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['power_output_type_id'].'"';
				if(isset($complete_data['power_output_type_id'][0]))
				{
					if($preset['power_output_type_id'] == $complete_data['power_output_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['power_output_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['power_output_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else if($settings['field'] == 'camera_max_resolution')
		{
			$precon = self::get_preset('resolution_units_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="resolution_units_type_id">';
			foreach ($precon['resolution_units_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['resolution_units_type_id'].'"';
				if(isset($complete_data['resolution_units_type_id'][0]))
				{
					if($preset['resolution_units_type_id'] == $complete_data['resolution_units_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['resolution_units_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['resolution_units_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else if(in_array($settings['field'], array('radio_frequency_to', 'radio_frequency_from', 'fm_transmitter_frequency_from', 'fm_transmitter_frequency_to')))
		{
			$precon = self::get_preset('frequency_unit_type');
			$input['input']['form'] .= '<select class="'.$class.'" name="frequency_unit_type_id">';
			foreach ($precon['frequency_unit_type'] as $preset)
			{
				$input['input']['form'] .= '<option value="'.$preset['frequency_unit_type_id'].'"';
				if(isset($complete_data['frequency_unit_type_id'][0]))
				{
					if($preset['frequency_unit_type_id'] == $complete_data['frequency_unit_type_id'][0])
					{
						$tmp = '<phrase id="'.$preset['frequency_unit_type_name'].'" quiet="true"/>';
						$input['input']['form'] .= ' selected="selected"';
					}
				}
				$input['input']['form'] .= '><phrase id="'.$preset['frequency_unit_type_name'].'" quiet="true"/></option>';
			}
			$input['input']['form'] .= '</select>';
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue.' '.$tmp.'</li></ul></div>';
		}
		else
		{
			$input['input']['text'] = '<div class="'.$class.'"><ul><li>'.$emptyvalue;
			if(strtolower($table) == 'radiation')
			{
				$input['input']['text'] .= ' W/kg ';
				if(isset($data) && $data != null)
				{
					if($data > 0 && $data <= 0.4)
					{
						$input['input']['text'] .= ' <phrase id="VERY_LOW" quiet="true"/>';
					}
					else if($data <= 0.6)
					{
						$input['input']['text'] .= ' <phrase id="LOW" quiet="true"/>';
					}
					else if($data <= 1.0)
					{
						$input['input']['text'] .= ' <phrase id="AVERAGE" quiet="true"/>';
					}
					else if($data > 1)
					{
						$input['input']['text'] .= ' <phrase id="HIGH" quiet="true"/>';
					}
				}
			}
			$input['input']['text'] .= '</li></ul></div>';
		}
		return $input;
	}

	private static function buildSelect($multiple=null, $data, $field, $table, $name)
	{
		if (self::$preset == null)
		{
			$precon = self::get_preset($field);
		}
		else
		{
			$precon = self::$preset;
		}
		if($table == 'phone' || $table == 'contacts' || $table == 'contacts_functions' || $table == 'caller_protocoll'|| $table == 'phone_function')
		{
			$table = 'contact_management';
		}
		else if($table == 'themes' || $table == 'ringtone_format' || $table == 'background_picture' || $table =='screensaver' || $table == 'profiles')
		{
			$table = 'personalization';
		}
		$field_name = $field;
		if($multiple !== null)
		{
			$field_name = $field.'[]';
		}
		$class = '';
		$input['label'] = strtoupper($name);
		$input['input']['form'] = "<select class='$class' name='".$field_name."' $multiple >";
		if(isset($precon[str_replace(array('_type_name','_type_id'), '_type', $field)]))
		{
			if($field == 'manufacturer_id')
			{
				Dobber::ReportNotice($field);
			}
			$input['input']['form'] .= self::buildOptions($precon[str_replace(array('_type_name','_type_id'), '_type', $field)], $data, $table, $field);
		}
		else if(isset($precon[str_replace(array('_name','_id'), '', $field)]))
		{
			$input['input']['form'] .= self::buildOptions($precon[str_replace(array('_name','_id'), '', $field)], $data, $table, $field);
		}
		else if(isset($precon[$table.'_type']))
		{
			$input['input']['form'] .= self::buildOptions($precon[$table.'_type'], $data, $table, $field);
		}
		$input['input']['form'] .= "</select>";
		if(!empty($data))
		{
			$input['input']['text'] = '<div class="'.$class.'"><ul>';
			if(empty($multiple))
			{
				if(is_array($data))
					$data = array_pop($data);
			}
			if(isset($precon[str_replace('_id', '', $field)]))
			{
				$input['input']['text'] .= self::buildNonInsertOptions($precon[str_replace('_id', '', $field)], $data, $table, $field);
			}
			else if(isset($precon[$table.'_type']))
			{
				$input['input']['text'] .= self::buildNonInsertOptions($precon[$table.'_type'], $data, $table, $field);
			}
			$input['input']['text'] .= '</ul></div>';
		}
		else
		{
			$input['input']['text'] = '<div class="'.$class.'"><ul><li><phrase id="UNKNOWN" quiet="true"/>';
			$input['input']['text'] .= '</li></ul>';
			$input['input']['text'] .= '</div>';
		}
		return $input;
	}

	private static function buildNonInsertOptions($precon, $data, $table, $field)
	{
		if(!is_array($data))
			$data = array($data);
		$input = '';
		foreach($precon as $preconset)
		{
			if(isset($preconset[$table.'_type_id']) && isset($preconset[$table.'_type_id']))
			{
				if(in_array($preconset[$table.'_type_id'], $data) !== false)
				{
					$input .= '<li>';
					if(($tmp = self::buildOptionValues($preconset, $table)) !== false)
					{
						$input .= $tmp;
					}
					else
					{
						$input .= '<phrase id="UNKNOWN" quiet="true"/>';
					}
					$input .= '</li>';
				}
			}
			else if(isset($preconset[$table.'_type_name']) !== false)
			{
				if(in_array($preconset[$table.'_type_name'], $data))
				{
					$input .= '<li>';
					if(($tmp = self::buildOptionValues($preconset, $table)) !== false)
					{
						$input .= $tmp;
					}
					else
					{
						$input .= '<phrase id="UNKNOWN" quiet="true"/>';
					}
					$input .= '</li>';
				}
			}
			else if(isset($preconset[$field]))
			{
				if(in_array($preconset[$field], $data) !== false)
				{
					$input .= '<li>';
					if(($tmp = self::buildOptionValues($preconset, $table, $field)) !== false)
					{
						$input .= $tmp;
					}
					else
					{
						$input .= '<phrase id="UNKNOWN" quiet="true"/>';
					}
					$input .= '</li>';
				}
			}
			else if(isset($preconset[str_replace('_id', '_name', $field)]))
			{
				if(in_array($preconset[str_replace('_id', '_name', $field)], $data) !== false)
				{
					$input .= '<li>';
					if(($tmp = self::buildOptionValues($preconset, $table)) !== false)
					{
						$input .= $tmp;
					}
					else
					{
						$input .= '<phrase id="UNKOWN" quiet="true"/>';
					}
					$input .= '</li>';
				}
			}
			else if(isset($preconset[str_replace('_name', '_id', $field)]))
			{
				if(in_array($preconset[str_replace('_name', '_id', $field)], $data) !== false)
				{
					$input .= '<li>';
					if(($tmp = self::buildOptionValues($preconset, $table)) !== false)
					{
						$input .= $tmp;
					}
					else
					{
						$input .= '<phrase id="UNKNOWN" quiet="true"/>';
					}
					$input .= '</li>';
				}
			}
			else $input = $table;
		}
		return $input;
	}

	private static function buildOptions($precon, $data, $table, $field)
	{
		if($table == 'phone' || $table == 'contacts' || $table == 'contacts_functions' || $table == 'caller_protocoll'|| $table == 'phone_function')
		{
			$table = 'contact_management';
		}
		else if($table == 'themes' || $table == 'ringtone_format' || $table == 'background_picture' || $table =='screensaver' || $table == 'profiles')
		{
			$table = 'personalization';
		}
		if(!is_array($data)) $data = array($data);
		$input = '';
		foreach($precon as $preconset)
		{
			if(isset($preconset[$table.'_type_id']) && isset($preconset[$table.'_type_shortname']))
			{
				$input .= '<option value="'.$preconset[$table.'_type_id'].'"';
				if(in_array($preconset[$table.'_type_id'], $data) !== false)
				{
					$input .= ' selected="selected"';
				}
				$input .= '>';
				$input .= self::buildOptionValues($preconset, $table);
				$input .='</option>';
			}
			else if(isset($preconset[$table.'_type_id']) && isset($preconset[$table.'_type_name']))
			{
				$input .= '<option value="'.$preconset[$table.'_type_id'].'"';
				if(in_array($preconset[$table.'_type_id'], $data) !== false)
				{
					$input .= ' selected="selected"';
				}
				$input .= '>';
				$input .= self::buildOptionValues($preconset, $table);
				$input .='</option>';
			}
			else if(isset($preconset[$table.'_type_name']) !== false)
			{
				$input .= '<option value="'.$preconset[$table.'_type_name'].'"';
				if(in_array($preconset[$table.'_type_name'], $data))
				{
					$input .= ' selected="selected"';
				}
				$input .= '>';
				$input .= self::buildOptionValues($preconset, $table);
				$input .='</option>';
			}
			else if(isset($preconset[$field]) !== false)
			{
				$input .= '<option value="'.$preconset[$field].'"';
				if(in_array($preconset[$field], $data))
				{
					$input .= ' selected="selected"';
				}
				$input .= '>';
				$input .= self::buildOptionValues($preconset, $table, $field);
				$input .='</option>';
			}
		}
		return $input;
	}

	private static function buildOptionValues($preconditions, $table, $field = '')
	{
		$input = false;
		if(isset($preconditions[$table.'_type_shortname']) && !empty($preconditions[$table.'_type_shortname']))
		{
			$input = '<phrase id="'.$preconditions[$table.'_type_shortname'].'" quiet="true"/>';
		}
		else if (isset($preconditions[$table.'_type_name']) && !empty($preconditions[$table.'_type_name']))
		{
			$input = '<phrase id="'.$preconditions[$table.'_type_name'].'" quiet="true"/>';
		}
		if(isset($preconditions[$table.'_type_amount']) && !empty($preconditions[$table.'_type_amount']))
		{
			$input = $preconditions[$table.'_type_amount'];
		}
		if (isset($preconditions[$table.'_type_frequency']) && $preconditions[$table.'_type_frequency'] != 0)
		{
			$input .= ' '.$preconditions[$table.'_type_frequency'];
		}
		if (isset($preconditions[$table.'_type_x']) && isset($preconditions[$table.'_type_y']))
		{
			$input .= ' '.$preconditions[$table.'_type_x'].' x '.$preconditions[$table.'_type_y'];
		}
		if (isset($preconditions[$table.'_type_version']))
		{
			$input .=  ' <phrase id="'.$preconditions[$table.'_type_version'].'"/>';
		}
		if (isset($preconditions[$table.'_type_short']) && $preconditions[$table.'_type_short'] != 0)
		{
			$input .= ' <phrase id="'.$preconditions[$table.'_type_short'].'" quiet="true"/>';
		}
		if (isset($preconditions[$table.'_type_compression']) && $preconditions[$table.'_type_compression'] != 0)
		{
			$input .= ' '.$preconditions[$table.'_type_compression'];
		}
		if (isset($preconditions[$table.'_type_standard']) && $preconditions[$table.'_type_standard'] != 0)
		{
			$input .= ' <phrase id="'.$preconditions[$table.'_type_standard'].'" quiet="true"/>';
		}
		if(!empty($field))
		{
			if (isset($preconditions[str_replace(array('_id', '_name'), '_shortname', $field)]))
			{
				$input = '<phrase id="'.$preconditions[str_replace(array('_id', '_name'), '_shortname', $field)].'" quiet="true"/>';
			}
			else if (isset($preconditions[str_replace(array('_id', '_name'), '_name', $field)]))
			{
				$input .= '<phrase id="'.$preconditions[str_replace(array('_id', '_name'), '_name', $field)].'" quiet="true"/>';
			}
			if (isset($preconditions[str_replace(array('_id', '_name'), '_amount', $field)]))
			{
				$input .= " ".$preconditions[str_replace(array('_id', '_name'), '_amount', $field)];
			}
			if (isset($preconditions[str_replace(array('_id', '_name'), '_frequency', $field)]))
			{
				$input .= " ".$preconditions[str_replace(array('_id', '_name'), '_frequency', $field)];
			}
			if (isset($preconditions[str_replace(array('_id', '_name'), '_x', $field)]) && isset($preconditions[str_replace(array('_id', '_name'), '_y', $field)]))
			{
				$input .= " ".$preconditions[str_replace(array('_id', '_name'), '_x', $field)].' x '.$preconditions[str_replace(array('_id', '_name'), '_y', $field)];
			}
			if (isset($preconditions[str_replace(array('_id', '_name'), '_standard', $field)]))
			{
				$input .= ' <phrase id="'.$preconditions[str_replace(array('_id', '_name'), '_standard', $field)].'" quiet="true"/>';
			}
			if (isset($preconditions[str_replace(array('_id', '_name'), '_compression', $field)]))
			{
				$input .= " ".$preconditions[str_replace(array('_id', '_name'), '_compression', $field)];
			}
			if (isset($preconditions[str_replace(array('_id', '_name'), '_short', $field)]))
			{
				$input .= ' <phrase id="'.$preconditions[str_replace(array('_id', '_name'), '_short', $field)].'" quiet="true"/>';
			}
			if (isset($preconditions[str_replace(array('_id', '_name'), '_version', $field)]))
			{
				$input .= ' <phrase id="'.$preconditions[str_replace(array('_id', '_name'), '_version', $field)].'"/>';
			}
		}
		return $input;
	}

	private static function build_decide($object_data, $field, $table, $settings)
	{
		if(self::$preset == null)
		{
			$precon = self::get_preset($settings['field']);
		}
		else
		{
			$precon = self::$preset;
		}
		$input['label'] = '<label for="'.$settings['field'].'"><phrase id="'.strtoupper($settings['field']).'" quiet="true"/></label>';
		$input['input'] = '<table>';
		foreach ($precon as $preconset)
		{
			$input['input'] .= '<tr><td>'.$preconset[$table.'_type_name'].'</td><td>';
			$input['input'] .= self::build_selection($preconset[$table.'_type_id']);
			$input['input'] .= '</td></tr>';
		}
		$input['input'] = '</table>';
	}

	private static function build_selection($id)
	{
		$selection = '<input type="radio" name="yes" value="'.$id.'"/>';
		$selection = '<input type="radio" name="no" value="'.$id.'"/>';
		$selection = '<input type="radio" name="maybe" value="'.$id.'"/>';
		return $selection;
	}

}
?>