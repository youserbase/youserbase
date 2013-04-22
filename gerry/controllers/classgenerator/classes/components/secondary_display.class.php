<?php
class secondary_display
{

	protected static $instance = null;
	private static $table = 'secondary_display';
	public static $data = array();
	public $secondary_display_id;
	public $component_id;
	public $component_id_int;
	public $display_type_id;
	public $display_resolution_x;
	public $display_resolution_y;
	public $display_size_diagonally;
	public $color_space_type_id;
	public $timestamp;
	public $youser_id;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($secondary_display_id = null)
	{
		if($secondary_display_id != null)
		{
			self::$secondary_display_id = $secondary_display_id;
		}
		if(self::$instance == null)
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}


	public function Loadview($component_id = null, $shift = 0)
	{
		$description['display_type_id'] = 'display_type_id';
		$description['display_resolution_x'] = 'display_resolution_x';
		$description['display_resolution_y'] = 'display_resolution_y';
		$description['display_size_diagonally'] = 'display_size_diagonally';
		$description['color_space_type_id'] = 'color_space_type_id';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM secondary_display  LEFT JOIN display_type ON secondary_display.display_type_id = display_type.display_type_id  LEFT JOIN color_space_type ON secondary_display.color_space_type_id = color_space_type.color_space_type_id  LEFT JOIN secondary_display AS t ON secondary_display.secondary_display_id = t.secondary_display_id WHERE secondary_display.component_id = ? ORDER BY secondary_display.timestamp DESC;", $component_id)->to_array();

		if(empty($response)) return $description;
		$data = array();

		foreach ($response as $set)
		{
			if(!isset($result[$set['timestamp']]))
			{
				$result[$set['timestamp']] = array($set);
			}
			else {
				array_push($result[$set['timestamp']], $set);
			}
		}
		unset($response);
		$data = array();
		$result = array_splice($result, $shift, 1);		foreach(reset($result) as $line => $content)
		{

			foreach($content as $line_name => $line_content)
			{
				if(strpos($line_name, '_id') === false && strpos($line_name, 'units') === false && strpos($line_name, 'memory_size_type') === false && strpos($line_name, 'currency_type_name') === false && strpos($line_name,'version') === false && strpos($line_name,'device') === false && strpos($line_name,'alternative') === false && strpos($line_name,'short') === false && strpos($line_name,'website') === false)
				{
					if(preg_match('/((\w+)?(_type))(_name\b)/', $line_name, $match))
					{
						if(isset($content[$match[1].'_name']))
						{
										if(!isset($data[$match[1].'_id']))
							{
								$data[$match[1].'_id'] = array($content[$match[1].'_name']);
							}
							else if(is_array($data[$match[1].'_id']) && !in_array($content[$match[1].'_name'], $data[$match[1].'_id']))
							{
								array_push($data[$match[1].'_id'], $content[$match[1].'_name']);
							}		}
						else
						{
							if(!isset($data[$match[1].'_name']))
							{
								$data[$match[1].'_id'] = $match[1].'_id';
							}
						}
					}
					else if($line_name == 'manufacturer_name')
					{
						if(isset($content['manufacturer_name']))
						{
							if(!isset($data['manufacturer_id'])  || !is_array($data['manufacturer_id']))
							{
								$data['manufacturer_id'] = array($content['manufacturer_name']);
							}
							else if(!in_array($content['manufacturer_name'], $data['manufacturer_id']))
							{
								array_push($data['manufacturer_id'], $content['manufacturer_name']);
							}
						}
						else
						{
							$data['manufacturer_id'] = 'manufacturer_id';
						}
					}
					else if(!isset($data[$line_name]))
					{
						if(strpos($line_name, 'length') ||strpos($line_name, 'width') || strpos($line_name, 'thickness') || strpos($line_name, 'size_diagonally') !== false && isset($content['size_units_type_name']))
						{
							$data[$line_name] = array('value' => $line_content, 'unit' => $content['size_units_type_name']);
						}
						else if(strpos($line_name, 'weight'))
						{
							$data[$line_name] = array('value' => $line_content, 'unit' => $content['weight_units_type_name']);
						}
						else if(strpos($line_name, 'retail_price'))
						{
							$data[$line_name] = array('value' => $line_content, 'unit' => $content['currency_type_name']);
						}
						else if(strpos($line_name, 'time'))
						{
							$data[$line_name] = array('value' => $line_content, 'unit' => $content['time_units_type_name']);
						}
						else if($line_name === 'internal_memory_size' || $line_name === 'extendable_memory_maxsize' || $line_name === 'ram_size')
						{
							$data[$line_name] = array('value' => $line_content, 'unit' => $content['memory_size_type_name']);
						}
						else
						{
							$data[$line_name] = array($line_content);
						}
					}
					else
					{
						if(is_array($data[$line_name]) && !in_array($line_content, $data[$line_name]) && !empty($data[$line_name]))
							array_push($data[$line_name], $line_content);
					}
				}
				if($line_name == 'youser_id')
				{
					$data['youser_id'] = $line_content;
				}
				if($line_name == 'timestamp')
				{
					$data['timestamp'] = $line_content;
				}
			}
		}
		return $data;
	}

	public function Loadedit($component_id = null)
	{
		$description['secondary_display_id'] = 'hidden';
		$description['component_id'] = 'hidden';
		$description['component_id_int'] = 'hidden';
		$description['display_type_id'] = 'select';
		$description['display_resolution_x'] = 'text';
		$description['display_resolution_y'] = 'text';
		$description['display_size_diagonally'] = 'text';
		$description['color_space_type_id'] = 'select';
		$description['timestamp'] = 'notshown';
		$description['youser_id'] = 'notshown';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM secondary_display  LEFT JOIN display_type ON secondary_display.display_type_id = display_type.display_type_id  LEFT JOIN color_space_type ON secondary_display.color_space_type_id = color_space_type.color_space_type_id  LEFT JOIN secondary_display AS t ON secondary_display.secondary_display_id = t.secondary_display_id WHERE secondary_display.component_id = ? ORDER BY secondary_display.timestamp DESC LIMIT 0,1;", $component_id)->to_array();
		if($result !== null){
			$data = array();
		
			foreach($result as $line => $content)
			{
				foreach($content as $line_name => $line_content)
				{
					if(!isset($data[$line_name]))
					{
						$data[$line_name] = array($line_content);
					}
					else if(is_array($data[$line_name]) && !in_array($line_content, $data[$line_name]) && !empty($data[$line_name]))
					{
						array_push($data[$line_name], $line_content);
					}
					if($line_name == 'youser_id')
					{
						$data['youser_id'] = $line_content;
					}
					if($line_name == 'timestamp')
					{
						$data['timestamp'] = $line_content;
					}
				}
			}
		}
		$preset = array();
		foreach($description as $line_name => $line_type)
		{
			if(($line_type == 'select' || $line_type == 'multiple' || strpos($line_name, 'type_id')) && strpos($line_name, 'type_id_int') === false)
			{
				$table = str_replace('_id', '', $line_name);
				$object = call_user_func_array(array($table, 'Get'), array());
				$preset[$line_name] = $object->Load();
			}
		}
		return array(self::$table => array('data' => $data, 'preset' => $preset, 'description' => $description));
	}

	public function Set($secondary_display_id = null, $component_id = null, $component_id_int = null, $display_type_id = null, $display_resolution_x = null, $display_resolution_y = null, $display_size_diagonally = null, $color_space_type_id = null, $timestamp = null, $youser_id = null)
	{
		if($secondary_display_id !== null)
		{
			self::$secondary_display_id = $secondary_display_id;
		}		if($component_id !== null)
		{
			self::$component_id = $component_id;
		}		if($component_id_int !== null)
		{
			self::$component_id_int = $component_id_int;
		}		if($display_type_id !== null)
		{
			self::$display_type_id = $display_type_id;
		}		if($display_resolution_x !== null)
		{
			self::$display_resolution_x = $display_resolution_x;
		}		if($display_resolution_y !== null)
		{
			self::$display_resolution_y = $display_resolution_y;
		}		if($display_size_diagonally !== null)
		{
			self::$display_size_diagonally = $display_size_diagonally;
		}		if($color_space_type_id !== null)
		{
			self::$color_space_type_id = $color_space_type_id;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES secondary_display WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO secondary_display (secondary_display_id, component_id, component_id_int, display_type_id, display_resolution_x, display_resolution_y, display_size_diagonally, color_space_type_id, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE secondary_display_id=VALUES(secondary_display_id),component_id=VALUES(component_id),component_id_int=VALUES(component_id_int),display_type_id=VALUES(display_type_id),display_resolution_x=VALUES(display_resolution_x),display_resolution_y=VALUES(display_resolution_y),display_size_diagonally=VALUES(display_size_diagonally),color_space_type_id=VALUES(color_space_type_id),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->secondary_display_id, $this->component_id, $this->component_id_int, $this->display_type_id, $this->display_resolution_x, $this->display_resolution_y, $this->display_size_diagonally, $this->color_space_type_id, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>