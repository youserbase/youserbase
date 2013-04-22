<?php
class mobilephone
{

	protected static $instance = null;
	private static $table = 'mobilephone';
	public static $data = array();
	public $mobilephone_id;
	public $mibilephone_id_int;
	public $component_id;
	public $component_id_int;
	public $indication_type_id;
	public $youser_id;
	public $timestamp;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($mobilephone_id = null)
	{
		if($mobilephone_id != null)
		{
			self::$mobilephone_id = $mobilephone_id;
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
		$description['indication_type_id'] = 'indication_type_id';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM mobilephone  LEFT JOIN indication_type ON mobilephone.indication_type_id = indication_type.indication_type_id  LEFT JOIN mobilephone AS t ON mobilephone.mobilephone_id = t.mobilephone_id WHERE mobilephone.component_id = ? ORDER BY mobilephone.timestamp DESC;", $component_id)->to_array();

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
		$description['mobilephone_id'] = 'hidden';
		$description['mibilephone_id_int'] = 'hidden';
		$description['component_id'] = 'hidden';
		$description['component_id_int'] = 'hidden';
		$description['indication_type_id'] = 'multiple';
		$description['youser_id'] = 'notshown';
		$description['timestamp'] = 'notshown';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM mobilephone  LEFT JOIN indication_type ON mobilephone.indication_type_id = indication_type.indication_type_id  LEFT JOIN mobilephone AS t ON mobilephone.mobilephone_id = t.mobilephone_id WHERE mobilephone.component_id = ? AND mobilephone.timestamp = (SELECT MAX(timestamp) FROM mobilephone WHERE mobilephone.component_id = ?);", $component_id, $component_id)->to_array();
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
		if(isset($_REQUEST['device_id']))
		{
			$device_id = $_REQUEST['device_id'];
			
			$device_type = DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id = ?;", $device_id)->fetch_item();
			
		}
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

	public function Set($mobilephone_id = null, $mibilephone_id_int = null, $component_id = null, $component_id_int = null, $indication_type_id = null, $youser_id = null, $timestamp = null)
	{
		if($mobilephone_id !== null)
		{
			self::$mobilephone_id = $mobilephone_id;
		}		if($mibilephone_id_int !== null)
		{
			self::$mibilephone_id_int = $mibilephone_id_int;
		}		if($component_id !== null)
		{
			self::$component_id = $component_id;
		}		if($component_id_int !== null)
		{
			self::$component_id_int = $component_id_int;
		}		if($indication_type_id !== null)
		{
			self::$indication_type_id = $indication_type_id;
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
		DBManager::Get('devices')->query("LOCK TABLES mobilephone WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO mobilephone (mobilephone_id, mibilephone_id_int, component_id, component_id_int, indication_type_id, youser_id, timestamp) VALUES(?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE mobilephone_id=VALUES(mobilephone_id),mibilephone_id_int=VALUES(mibilephone_id_int),component_id=VALUES(component_id),component_id_int=VALUES(component_id_int),indication_type_id=VALUES(indication_type_id),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->mobilephone_id, $this->mibilephone_id_int, $this->component_id, $this->component_id_int, $this->indication_type_id, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>