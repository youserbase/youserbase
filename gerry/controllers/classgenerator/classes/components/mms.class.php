<?php
class mms
{

	protected static $instance = null;
	private static $table = 'mms';
	public static $data = array();
	public $mms_id;
	public $component_id;
	public $component_id_int;
	public $mms_max_size;
	public $memory_size_type_id;
	public $mms_number_of_storeable_intern;
	public $mms_number_of_storeable_extern;
	public $timestamp;
	public $youser_id;
	public $message_function_type_id;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($mms_id = null)
	{
		if($mms_id != null)
		{
			self::$mms_id = $mms_id;
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
		$description['mms_max_size'] = 'mms_max_size';
		$description['mms_number_of_storeable_intern'] = 'mms_number_of_storeable_intern';
		$description['mms_number_of_storeable_extern'] = 'mms_number_of_storeable_extern';
		$description['message_function_type_id'] = 'message_function_type_id';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM mms  LEFT JOIN memory_size_type ON mms.memory_size_type_id = memory_size_type.memory_size_type_id  LEFT JOIN message_function_type ON mms.message_function_type_id = message_function_type.message_function_type_id  LEFT JOIN mms AS t ON mms.mms_id = t.mms_id WHERE mms.component_id = ? ORDER BY mms.timestamp DESC;", $component_id)->to_array();

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
		$description['mms_id'] = 'hidden';
		$description['component_id'] = 'hidden';
		$description['component_id_int'] = 'hidden';
		$description['mms_max_size'] = 'text';
		$description['memory_size_type_id'] = 'notshown';
		$description['mms_number_of_storeable_intern'] = 'text';
		$description['mms_number_of_storeable_extern'] = 'text';
		$description['timestamp'] = 'notshown';
		$description['youser_id'] = 'notshown';
		$description['message_function_type_id'] = 'multiple';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM mms  LEFT JOIN memory_size_type ON mms.memory_size_type_id = memory_size_type.memory_size_type_id  LEFT JOIN message_function_type ON mms.message_function_type_id = message_function_type.message_function_type_id  LEFT JOIN mms AS t ON mms.mms_id = t.mms_id WHERE mms.component_id = ? AND mms.timestamp = (SELECT MAX(timestamp) FROM mms WHERE mms.component_id = ?);", $component_id, $component_id)->to_array();
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

	public function Set($mms_id = null, $component_id = null, $component_id_int = null, $mms_max_size = null, $memory_size_type_id = null, $mms_number_of_storeable_intern = null, $mms_number_of_storeable_extern = null, $timestamp = null, $youser_id = null, $message_function_type_id = null)
	{
		if($mms_id !== null)
		{
			self::$mms_id = $mms_id;
		}		if($component_id !== null)
		{
			self::$component_id = $component_id;
		}		if($component_id_int !== null)
		{
			self::$component_id_int = $component_id_int;
		}		if($mms_max_size !== null)
		{
			self::$mms_max_size = $mms_max_size;
		}		if($memory_size_type_id !== null)
		{
			self::$memory_size_type_id = $memory_size_type_id;
		}		if($mms_number_of_storeable_intern !== null)
		{
			self::$mms_number_of_storeable_intern = $mms_number_of_storeable_intern;
		}		if($mms_number_of_storeable_extern !== null)
		{
			self::$mms_number_of_storeable_extern = $mms_number_of_storeable_extern;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}		if($youser_id !== null)
		{
			self::$youser_id = $youser_id;
		}		if($message_function_type_id !== null)
		{
			self::$message_function_type_id = $message_function_type_id;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES mms WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO mms (mms_id, component_id, component_id_int, mms_max_size, memory_size_type_id, mms_number_of_storeable_intern, mms_number_of_storeable_extern, timestamp, youser_id, message_function_type_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE mms_id=VALUES(mms_id),component_id=VALUES(component_id),component_id_int=VALUES(component_id_int),mms_max_size=VALUES(mms_max_size),memory_size_type_id=VALUES(memory_size_type_id),mms_number_of_storeable_intern=VALUES(mms_number_of_storeable_intern),mms_number_of_storeable_extern=VALUES(mms_number_of_storeable_extern),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id),message_function_type_id=VALUES(message_function_type_id);", $this->mms_id, $this->component_id, $this->component_id_int, $this->mms_max_size, $this->memory_size_type_id, $this->mms_number_of_storeable_intern, $this->mms_number_of_storeable_extern, $this->timestamp, $this->youser_id, $this->message_function_type_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>