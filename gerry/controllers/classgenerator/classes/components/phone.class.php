<?php
class phone
{

	protected static $instance = null;
	private static $table = 'phone';
	public static $data = array();
	public $phone_id;
	public $component_id;
	public $component_id_int;
	public $phone_number_of_call_groups;
	public $phone_number_of_profiles;
	public $timestamp;
	public $youser_id;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($phone_id = null)
	{
		if($phone_id != null)
		{
			self::$phone_id = $phone_id;
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
		$description['phone_number_of_call_groups'] = 'phone_number_of_call_groups';
		$description['phone_number_of_profiles'] = 'phone_number_of_profiles';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM phone  WHERE phone.component_id = ? ORDER BY phone.timestamp DESC;", $component_id)->to_array();

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
		$description['phone_id'] = 'hidden';
		$description['component_id'] = 'hidden';
		$description['component_id_int'] = 'hidden';
		$description['phone_number_of_call_groups'] = 'text';
		$description['phone_number_of_profiles'] = 'text';
		$description['timestamp'] = 'notshown';
		$description['youser_id'] = 'notshown';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM phone  WHERE phone.component_id = ? ORDER BY phone.timestamp DESC LIMIT 0,1;", $component_id)->to_array();
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

	public function Set($phone_id = null, $component_id = null, $component_id_int = null, $phone_number_of_call_groups = null, $phone_number_of_profiles = null, $timestamp = null, $youser_id = null)
	{
		if($phone_id !== null)
		{
			self::$phone_id = $phone_id;
		}		if($component_id !== null)
		{
			self::$component_id = $component_id;
		}		if($component_id_int !== null)
		{
			self::$component_id_int = $component_id_int;
		}		if($phone_number_of_call_groups !== null)
		{
			self::$phone_number_of_call_groups = $phone_number_of_call_groups;
		}		if($phone_number_of_profiles !== null)
		{
			self::$phone_number_of_profiles = $phone_number_of_profiles;
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
		DBManager::Get('devices')->query("LOCK TABLES phone WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO phone (phone_id, component_id, component_id_int, phone_number_of_call_groups, phone_number_of_profiles, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE phone_id=VALUES(phone_id),component_id=VALUES(component_id),component_id_int=VALUES(component_id_int),phone_number_of_call_groups=VALUES(phone_number_of_call_groups),phone_number_of_profiles=VALUES(phone_number_of_profiles),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->phone_id, $this->component_id, $this->component_id_int, $this->phone_number_of_call_groups, $this->phone_number_of_profiles, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>