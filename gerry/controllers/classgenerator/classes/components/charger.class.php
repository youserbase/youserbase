<?php
class charger
{

	protected static $instance = null;
	private static $table = 'charger';
	public static $data = array();
	public $charger_id;
	public $component_id;
	public $component_id_int;
	public $charger_model_name;
	public $manufacturer_id;
	public $charger_input_ampere;
	public $charger_input_volts_from;
	public $charger_input_volts_to;
	public $charger_output_ampere;
	public $charger_output_volt;
	public $timestamp;
	public $youser_id;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($charger_id = null)
	{
		if($charger_id != null)
		{
			self::$charger_id = $charger_id;
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
		$description['charger_model_name'] = 'charger_model_name';
		$description['manufacturer_id'] = 'manufacturer_id';
		$description['charger_input_ampere'] = 'charger_input_ampere';
		$description['charger_input_volts_from'] = 'charger_input_volts_from';
		$description['charger_input_volts_to'] = 'charger_input_volts_to';
		$description['charger_output_ampere'] = 'charger_output_ampere';
		$description['charger_output_volt'] = 'charger_output_volt';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM charger  LEFT JOIN manufacturer ON charger.manufacturer_Id = manufacturer.manufacturer_id  LEFT JOIN charger AS t ON charger.charger_id = t.charger_id WHERE charger.component_id = ? ORDER BY charger.timestamp DESC;", $component_id)->to_array();

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
		$description['charger_id'] = 'hidden';
		$description['component_id'] = 'hidden';
		$description['component_id_int'] = 'hidden';
		$description['charger_model_name'] = 'text';
		$description['manufacturer_id'] = 'select';
		$description['charger_input_ampere'] = 'text';
		$description['charger_input_volts_from'] = 'text';
		$description['charger_input_volts_to'] = 'text';
		$description['charger_output_ampere'] = 'text';
		$description['charger_output_volt'] = 'text';
		$description['timestamp'] = 'notshown';
		$description['youser_id'] = 'notshown';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM charger  WHERE charger.component_id = ? ORDER BY charger.timestamp DESC LIMIT 0,1;", $component_id)->to_array();
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

	public function Set($charger_id = null, $component_id = null, $component_id_int = null, $charger_model_name = null, $manufacturer_id = null, $charger_input_ampere = null, $charger_input_volts_from = null, $charger_input_volts_to = null, $charger_output_ampere = null, $charger_output_volt = null, $timestamp = null, $youser_id = null)
	{
		if($charger_id !== null)
		{
			self::$charger_id = $charger_id;
		}		if($component_id !== null)
		{
			self::$component_id = $component_id;
		}		if($component_id_int !== null)
		{
			self::$component_id_int = $component_id_int;
		}		if($charger_model_name !== null)
		{
			self::$charger_model_name = $charger_model_name;
		}		if($manufacturer_id !== null)
		{
			self::$manufacturer_id = $manufacturer_id;
		}		if($charger_input_ampere !== null)
		{
			self::$charger_input_ampere = $charger_input_ampere;
		}		if($charger_input_volts_from !== null)
		{
			self::$charger_input_volts_from = $charger_input_volts_from;
		}		if($charger_input_volts_to !== null)
		{
			self::$charger_input_volts_to = $charger_input_volts_to;
		}		if($charger_output_ampere !== null)
		{
			self::$charger_output_ampere = $charger_output_ampere;
		}		if($charger_output_volt !== null)
		{
			self::$charger_output_volt = $charger_output_volt;
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
		DBManager::Get('devices')->query("LOCK TABLES charger WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO charger (charger_id, component_id, component_id_int, charger_model_name, manufacturer_id, charger_input_ampere, charger_input_volts_from, charger_input_volts_to, charger_output_ampere, charger_output_volt, timestamp, youser_id) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE charger_id=VALUES(charger_id),component_id=VALUES(component_id),component_id_int=VALUES(component_id_int),charger_model_name=VALUES(charger_model_name),manufacturer_id=VALUES(manufacturer_id),charger_input_ampere=VALUES(charger_input_ampere),charger_input_volts_from=VALUES(charger_input_volts_from),charger_input_volts_to=VALUES(charger_input_volts_to),charger_output_ampere=VALUES(charger_output_ampere),charger_output_volt=VALUES(charger_output_volt),timestamp=VALUES(timestamp),youser_id=VALUES(youser_id);", $this->charger_id, $this->component_id, $this->component_id_int, $this->charger_model_name, $this->manufacturer_id, $this->charger_input_ampere, $this->charger_input_volts_from, $this->charger_input_volts_to, $this->charger_output_ampere, $this->charger_output_volt, $this->timestamp, $this->youser_id);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>