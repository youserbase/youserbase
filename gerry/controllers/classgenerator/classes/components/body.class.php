<?php
class body
{

	protected static $instance = null;
	private static $table = 'body';
	public static $data = array();
	public $body_id;
	public $component_id;
	public $component_id_int;
	public $body_type_id;
	public $body_weight;
	public $weight_units_type_id;
	public $body_length;
	public $body_width;
	public $body_thickness;
	public $size_units_type_id;
	public $timestamp;
	public $youser_id;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($body_id = null)
	{
		if($body_id != null)
		{
			self::$body_id = $body_id;
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
		$description['body_type_id'] = 'body_type_id';
		$description['body_weight'] = 'body_weight';
		$description['body_length'] = 'body_length';
		$description['body_width'] = 'body_width';
		$description['body_thickness'] = 'body_thickness';
		if($component_id == null) return $description;
		$device_id = DBManager::Get('devices')->query("SELECT DISTINCT(device_id) FROM device_components WHERE component_id = ?;", $component_id)->fetch_item();
		$device_type = DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id = ?;", $device_id)->fetch_item();
		$response = DBManager::Get('devices')->query("SELECT * FROM body LEFT JOIN body_type ON body.body_type_id = body_type.body_type_id  LEFT JOIN weight_units_type ON body.weight_units_type_id = weight_units_type.weight_units_type_id  LEFT JOIN size_units_type ON body.size_units_type_id = size_units_type.size_units_type_id  LEFT JOIN body AS t ON body.body_id = t.body_id WHERE body.component_id = ? ORDER BY body.timestamp DESC;", $component_id)->to_array();

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
		$description['body_id'] = 'hidden';
		$description['component_id'] = 'hidden';
		$description['component_id_int'] = 'hidden';
		$description['body_type_id'] = 'select';
		$description['body_weight'] = 'text';
		$description['weight_units_type_id'] = 'notshown';
		$description['body_length'] = 'text';
		$description['body_width'] = 'text';
		$description['body_thickness'] = 'text';
		$description['size_units_type_id'] = 'notshown';
		$description['timestamp'] = 'notshown';
		$description['youser_id'] = 'notshown';

		if(isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
			$device_type = DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id = ?;", $device_id)->fetch_item();
		}
		
		$result = DBManager::Get('devices')->query("SELECT * FROM body  LEFT JOIN body_type ON body.body_type_id = body_type.body_type_id  LEFT JOIN weight_units_type ON body.weight_units_type_id = weight_units_type.weight_units_type_id  LEFT JOIN size_units_type ON body.size_units_type_id = size_units_type.size_units_type_id  LEFT JOIN body AS t ON body.body_id = t.body_id WHERE body.component_id = ? ORDER BY body.timestamp DESC LIMIT 0,1;", $component_id)->to_array();
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
				$object = call_user_func_array(array($table, 'Get'), array(null, $device_type));
				$preset[$line_name] = $object->Load(null, $device_type);
			}
		}
		return array(self::$table => array('data' => $data, 'preset' => $preset, 'description' => $description));
	}

	public function Set($body_id = null, $component_id = null, $component_id_int = null, $body_type_id = null, $body_weight = null, $weight_units_type_id = null, $body_length = null, $body_width = null, $body_thickness = null, $size_units_type_id = null, $timestamp = null, $youser_id = null)
	{
		if($body_id !== null)
		{
			self::$body_id = $body_id;
		}		if($component_id !== null)
		{
			self::$component_id = $component_id;
		}		if($component_id_int !== null)
		{
			self::$component_id_int = $component_id_int;
		}		if($body_type_id !== null)
		{
			self::$body_type_id = $body_type_id;
		}		if($body_weight !== null)
		{
			self::$body_weight = $body_weight;
		}		if($weight_units_type_id !== null)
		{
			self::$weight_units_type_id = $weight_units_type_id;
		}		if($body_length !== null)
		{
			self::$body_length = $body_length;
		}		if($body_width !== null)
		{
			self::$body_width = $body_width;
		}		if($body_thickness !== null)
		{
			self::$body_thickness = $body_thickness;
		}		if($size_units_type_id !== null)
		{
			self::$size_units_type_id = $size_units_type_id;
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
		DBManager::Get('devices')->query("LOCK TABLES body WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO body (body_id, component_id, component_id_int, body_type_id, body_weight, weight_units_type_id, body_length, body_width, body_thickness, size_units_type_id, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE body_id=VALUES(body_id),component_id=VALUES(component_id),component_id_int=VALUES(component_id_int),body_type_id=VALUES(body_type_id),body_weight=VALUES(body_weight),weight_units_type_id=VALUES(weight_units_type_id),body_length=VALUES(body_length),body_width=VALUES(body_width),body_thickness=VALUES(body_thickness),size_units_type_id=VALUES(size_units_type_id),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->body_id, $this->component_id, $this->component_id_int, $this->body_type_id, $this->body_weight, $this->weight_units_type_id, $this->body_length, $this->body_width, $this->body_thickness, $this->size_units_type_id, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>