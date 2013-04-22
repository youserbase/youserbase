<?php
class search_index
{

	protected static $instance = null;
	private static $table = 'search_index';
	public static $data = array();
	public $device_id_int;
	public $device_name;
	public $manufacturer_name;
	public $manufacturer_id;
	public $colors;
	public $components;
	public $indication;
	public $operatingsystem;
	public $status;
	public $release_year;
	public $build_form;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($device_id_int = null)
	{
		if($device_id_int != null)
		{
			self::$device_id_int = $device_id_int;
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
		$description['device_id_int'] = 'device_id_int';
		$description['device_name'] = 'device_name';
		$description['manufacturer_name'] = 'manufacturer_name';
		$description['manufacturer_id'] = 'manufacturer_id';
		$description['colors'] = 'colors';
		$description['components'] = 'components';
		$description['indication'] = 'indication';
		$description['operatingsystem'] = 'operatingsystem';
		$description['status'] = 'status';
		$description['release_year'] = 'release_year';
		$description['build_form'] = 'build_form';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM search_index  LEFT JOIN manufacturer ON search_index.manufacturer_Id = manufacturer.manufacturer_id  LEFT JOIN search_index AS t ON search_index.search_index_id = t.search_index_id WHERE search_index.component_id = ? ORDER BY search_index.timestamp DESC;", $component_id)->to_array();

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
		$description['device_id_int'] = '';
		$description['device_name'] = '';
		$description['manufacturer_name'] = '';
		$description['manufacturer_id'] = '';
		$description['colors'] = '';
		$description['components'] = '';
		$description['indication'] = '';
		$description['operatingsystem'] = '';
		$description['status'] = '';
		$description['release_year'] = '';
		$description['build_form'] = '';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM search_index  WHERE search_index.component_id = ? ORDER BY search_index.timestamp DESC LIMIT 0,1;", $component_id)->to_array();
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

	public function Set($device_id_int = null, $device_name = null, $manufacturer_name = null, $manufacturer_id = null, $colors = null, $components = null, $indication = null, $operatingsystem = null, $status = null, $release_year = null, $build_form = null)
	{
		if($device_id_int !== null)
		{
			self::$device_id_int = $device_id_int;
		}		if($device_name !== null)
		{
			self::$device_name = $device_name;
		}		if($manufacturer_name !== null)
		{
			self::$manufacturer_name = $manufacturer_name;
		}		if($manufacturer_id !== null)
		{
			self::$manufacturer_id = $manufacturer_id;
		}		if($colors !== null)
		{
			self::$colors = $colors;
		}		if($components !== null)
		{
			self::$components = $components;
		}		if($indication !== null)
		{
			self::$indication = $indication;
		}		if($operatingsystem !== null)
		{
			self::$operatingsystem = $operatingsystem;
		}		if($status !== null)
		{
			self::$status = $status;
		}		if($release_year !== null)
		{
			self::$release_year = $release_year;
		}		if($build_form !== null)
		{
			self::$build_form = $build_form;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES search_index WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO search_index (device_id_int, device_name, manufacturer_name, manufacturer_id, colors, components, indication, operatingsystem, status, release_year, build_form) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE device_id_int=VALUES(device_id_int),device_name=VALUES(device_name),manufacturer_name=VALUES(manufacturer_name),manufacturer_id=VALUES(manufacturer_id),colors=VALUES(colors),components=VALUES(components),indication=VALUES(indication),operatingsystem=VALUES(operatingsystem),status=VALUES(status),release_year=VALUES(release_year),build_form=VALUES(build_form);", $this->device_id_int, $this->device_name, $this->manufacturer_name, $this->manufacturer_id, $this->colors, $this->components, $this->indication, $this->operatingsystem, $this->status, $this->release_year, $this->build_form);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>