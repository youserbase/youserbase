<?php
class projection
{

	protected static $instance = null;
	private static $table = 'projection';
	public static $data = array();
	public $projection_id;
	public $component_id;
	public $component_id_int;
	public $projection_type_id;
	public $projection_brightness;
	public $resolution_type_id;
	public $projection_min_distance;
	public $projection_max_distance;
	public $projection_min_size_diagonally;
	public $projection_max_size_diagonally;
	public $size_units_type_id;
	public $youser_id;
	public $timestamp;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($projection_id = null)
	{
		if($projection_id != null)
		{
			self::$projection_id = $projection_id;
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
		$description['projection_type_id'] = 'projection_type_id';
		$description['projection_brightness'] = 'projection_brightness';
		$description['resolution_type_id'] = 'resolution_type_id';
		$description['projection_min_distance'] = 'projection_min_distance';
		$description['projection_max_distance'] = 'projection_max_distance';
		$description['projection_min_size_diagonally'] = 'projection_min_size_diagonally';
		$description['projection_max_size_diagonally'] = 'projection_max_size_diagonally';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM projection  LEFT JOIN projection_type ON projection.projection_type_id = projection_type.projection_type_id  LEFT JOIN resolution_type ON projection.resolution_type_id = resolution_type.resolution_type_id  LEFT JOIN size_units_type ON projection.size_units_type_id = size_units_type.size_units_type_id  LEFT JOIN projection AS t ON projection.projection_id = t.projection_id WHERE projection.component_id = ? ORDER BY projection.timestamp DESC;", $component_id)->to_array();

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
		$description['projection_id'] = 'hidden';
		$description['component_id'] = 'hidden';
		$description['component_id_int'] = 'hidden';
		$description['projection_type_id'] = 'multiple';
		$description['projection_brightness'] = 'text';
		$description['resolution_type_id'] = 'multiple';
		$description['projection_min_distance'] = 'text';
		$description['projection_max_distance'] = 'text';
		$description['projection_min_size_diagonally'] = 'text';
		$description['projection_max_size_diagonally'] = 'text';
		$description['size_units_type_id'] = 'notshown';
		$description['youser_id'] = 'notshown';
		$description['timestamp'] = 'notshown';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM projection  LEFT JOIN projection_type ON projection.projection_type_id = projection_type.projection_type_id  LEFT JOIN resolution_type ON projection.resolution_type_id = resolution_type.resolution_type_id  LEFT JOIN size_units_type ON projection.size_units_type_id = size_units_type.size_units_type_id  LEFT JOIN projection AS t ON projection.projection_id = t.projection_id WHERE projection.component_id = ? AND projection.timestamp = (SELECT MAX(timestamp) FROM projection WHERE projection.component_id = ?);", $component_id, $component_id)->to_array();
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

	public function Set($projection_id = null, $component_id = null, $component_id_int = null, $projection_type_id = null, $projection_brightness = null, $resolution_type_id = null, $projection_min_distance = null, $projection_max_distance = null, $projection_min_size_diagonally = null, $projection_max_size_diagonally = null, $size_units_type_id = null, $youser_id = null, $timestamp = null)
	{
		if($projection_id !== null)
		{
			self::$projection_id = $projection_id;
		}		if($component_id !== null)
		{
			self::$component_id = $component_id;
		}		if($component_id_int !== null)
		{
			self::$component_id_int = $component_id_int;
		}		if($projection_type_id !== null)
		{
			self::$projection_type_id = $projection_type_id;
		}		if($projection_brightness !== null)
		{
			self::$projection_brightness = $projection_brightness;
		}		if($resolution_type_id !== null)
		{
			self::$resolution_type_id = $resolution_type_id;
		}		if($projection_min_distance !== null)
		{
			self::$projection_min_distance = $projection_min_distance;
		}		if($projection_max_distance !== null)
		{
			self::$projection_max_distance = $projection_max_distance;
		}		if($projection_min_size_diagonally !== null)
		{
			self::$projection_min_size_diagonally = $projection_min_size_diagonally;
		}		if($projection_max_size_diagonally !== null)
		{
			self::$projection_max_size_diagonally = $projection_max_size_diagonally;
		}		if($size_units_type_id !== null)
		{
			self::$size_units_type_id = $size_units_type_id;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES projection WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO projection (projection_id, component_id, component_id_int, projection_type_id, projection_brightness, resolution_type_id, projection_min_distance, projection_max_distance, projection_min_size_diagonally, projection_max_size_diagonally, size_units_type_id, youser_id, timestamp) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE projection_id=VALUES(projection_id),component_id=VALUES(component_id),component_id_int=VALUES(component_id_int),projection_type_id=VALUES(projection_type_id),projection_brightness=VALUES(projection_brightness),resolution_type_id=VALUES(resolution_type_id),projection_min_distance=VALUES(projection_min_distance),projection_max_distance=VALUES(projection_max_distance),projection_min_size_diagonally=VALUES(projection_min_size_diagonally),projection_max_size_diagonally=VALUES(projection_max_size_diagonally),size_units_type_id=VALUES(size_units_type_id),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->projection_id, $this->component_id, $this->component_id_int, $this->projection_type_id, $this->projection_brightness, $this->resolution_type_id, $this->projection_min_distance, $this->projection_max_distance, $this->projection_min_size_diagonally, $this->projection_max_size_diagonally, $this->size_units_type_id, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>