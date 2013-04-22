<?php
class compareSheetBuilder
{
	public static function buildForm($precon='', $fields='', $rating ='', $object_data, $table)
	{
		foreach($fields as $field => $settings)
		{
			$input[$settings['field']]['id'] = $field;
			$class = '';
			$start = strpos($settings['type'], '(');
			$length = substr($settings['type'],$start);
			$length = str_replace(')', '', $length);
			$length = str_replace('(', '', $length);
			if($length > 50)
				$length = 50;
			if(strpos($settings['type'], 'int') !== false || strpos($settings['type'], 'decimal') !== false)
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
					$input[$settings['field']] = self::buildText($settings, $object_data[$field]);
					break;
				case 'text':
					$input[$settings['field']] = self::buildText($settings, $object_data[$field]);
					break;
				case 'select':
					$input[$settings['field']] = self::buildSelect($settings, $precon, $object_data[$field], $field, $table);	
					break;
				case 'multiple':
					$input[$settings['field']] = self::buildSelect($settings, $precon, $object_data[$field], $field, $table);	
					break;
			}
		}
		return $input;
	}
	
	private static function buildText($settings, $data)
	{
		if(is_array($data))
		{
			$data = array_pop($data);
		}
		$input['label'] = $settings['field'];
		if(empty($data))
		{
			$input['input'] = '<phrase id="NOT_SET"/>';
			return $input;
		}
		$input['input'] = $data;
		return $input;
	}
	
	private static function buildSelect($settings, $precon, $data, $field, $table)
	{
		$name = $settings['field'];
		if(isset($precon[str_replace('_id','',$settings['field'])]))
		{
			$input['input'] = self::buildOptions($precon[str_replace('_id','',$settings['field'])], $settings, $field, $data, $table);
			$input['label'] = $settings['field'];
			return $input;
		}
		else if(isset($precon[$table.'_type']))
		{
			$input['input'] = self::buildOptions($precon[$table.'_type'], $settings, $field, $data, $table);
			$input['label'] = $settings['field'];
			return $input;
		}
		return false;
	}
	
	private static function buildOptions($precon, $settings, $field, $data, $table)
	{
		$input = false;
		foreach($precon as $value)
		{
			if(isset($value[$table.'_type_id']))
			{
				if(is_array($data))
				{
					if(array_search($value[$table.'_type_id'], $data) !== false)
					{
						$input[] = self::fillOptions($value, $table);
					}
				}
				else
				{
					if($value[$table.'_type_id'] == $data)
					{
						$input[] = self::fillOptions($value, $table);
					}
				}
				
			}
			else if(isset($value[$table.'_type_name']))
			{
				if(is_array($data))
				{
					if(array_search($value[$table.'_type_name'], $data) !== false)
					{
						$input[] = self::fillOptions($value, $table);
					}
				}
				else
				{
					if(isset($value[$table.'_type_name']))
					{
						if($value[$table.'_type_name'] == $data)
						{
							$input[] = self::fillOptions($value, $table);
						}
					}
				}
			}
		}
		return $input;
	}
	
	private static function fillOptions($value, $table)
	{
		$input = '';
		if(isset($value[''.$table.'_type_shortname']) && !empty($value[''.$table.'_type_shortname']) && !is_array($value[''.$table.'_type_shortname']))
		{
			 $input .= $value[''.$table.'_type_shortname'];
		}
		else if(isset($value[''.$table.'_type_name']) && !is_array($value[''.$table.'_type_name']))
		{
			$input .= "<phrase id=".$value[''.$table.'_type_name']."/>";
		}
		if(isset($value[$table.'_type_frequency']) && !is_array($value[''.$table.'_type_frequency']))
		{
			if(!empty($value[$table.'_type_frequency']) && $value[$table.'_type_frequency'] != '0')
				$input .= " ".$value[$table.'_type_frequency'];
		}
		if(isset($value[$table.'_type_standard']) && !is_array($value[''.$table.'_type_standard']))
		{
			if(!empty($value[$table.'_type_standard']) && $value[$table.'_type_standard'] != '0')
				$input .= " ".$value[$table.'_type_standard'];
		}
		if(isset($value[$table.'_type_compression'])  && !is_array($value[''.$table.'_type_compression']))
		{
			if(!empty($value[$table.'_type_compression']) && $value[$table.'_type_compression'] != '0')
			 	$input .= " ".$value[$table.'_type_compression'];
		}
		if(isset($value[$table.'_type_short']) && !is_array($value[''.$table.'_type_short']))
		{
			if(!empty($value[$table.'_type_short']))
				$input .= " .".strtolower($value[$table.'_type_short']);
		}
		if(isset($value[$table.'_type_version']) && !is_array($value[''.$table.'_type_version']))
		{
			 $input .= " <phrase id=".strtolower($value[$table.'_type_version'])."/>";
		}
		if(!is_array($input))
			return $input;
	}
}
?>