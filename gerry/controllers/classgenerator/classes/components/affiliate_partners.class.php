<?php
class affiliate_partners
{

	protected static $instance = null;
	private static $table = 'affiliate_partners';
	public static $data = array();
	public $affiliate_id;
	public $device_id;
	public $device_id_int;
	public $language;
	public $price;
	public $shipping;
	public $currency;
	public $store_name;
	public $store_url;
	public $store_logo;
	public $timestamp;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($affiliate_id = null)
	{
		if($affiliate_id != null)
		{
			self::$affiliate_id = $affiliate_id;
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
		$description['affiliate_id'] = 'affiliate_id';
		$description['device_id'] = 'device_id';
		$description['device_id_int'] = 'device_id_int';
		$description['language'] = 'language';
		$description['price'] = 'price';
		$description['shipping'] = 'shipping';
		$description['currency'] = 'currency';
		$description['store_name'] = 'store_name';
		$description['store_url'] = 'store_url';
		$description['store_logo'] = 'store_logo';
		$description['timestamp'] = 'timestamp';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM affiliate_partners  WHERE affiliate_partners.component_id = ? ORDER BY affiliate_partners.timestamp DESC;", $component_id)->to_array();

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
		$description['affiliate_id'] = '';
		$description['device_id'] = '';
		$description['device_id_int'] = '';
		$description['language'] = '';
		$description['price'] = '';
		$description['shipping'] = '';
		$description['currency'] = '';
		$description['store_name'] = '';
		$description['store_url'] = '';
		$description['store_logo'] = '';
		$description['timestamp'] = '';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM affiliate_partners  WHERE affiliate_partners.component_id = ? ORDER BY affiliate_partners.timestamp DESC LIMIT 0,1;", $component_id)->to_array();
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

	public function Set($affiliate_id = null, $device_id = null, $device_id_int = null, $language = null, $price = null, $shipping = null, $currency = null, $store_name = null, $store_url = null, $store_logo = null, $timestamp = null)
	{
		if($affiliate_id !== null)
		{
			self::$affiliate_id = $affiliate_id;
		}		if($device_id !== null)
		{
			self::$device_id = $device_id;
		}		if($device_id_int !== null)
		{
			self::$device_id_int = $device_id_int;
		}		if($language !== null)
		{
			self::$language = $language;
		}		if($price !== null)
		{
			self::$price = $price;
		}		if($shipping !== null)
		{
			self::$shipping = $shipping;
		}		if($currency !== null)
		{
			self::$currency = $currency;
		}		if($store_name !== null)
		{
			self::$store_name = $store_name;
		}		if($store_url !== null)
		{
			self::$store_url = $store_url;
		}		if($store_logo !== null)
		{
			self::$store_logo = $store_logo;
		}		if($timestamp !== null)
		{
			self::$timestamp = $timestamp;
		}
	}

	public function save()
	{
		DBManager::Get('devices')->query("LOCK TABLES affiliate_partners WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO affiliate_partners (affiliate_id, device_id, device_id_int, language, price, shipping, currency, store_name, store_url, store_logo, timestamp) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE affiliate_id=VALUES(affiliate_id),device_id=VALUES(device_id),device_id_int=VALUES(device_id_int),language=VALUES(language),price=VALUES(price),shipping=VALUES(shipping),currency=VALUES(currency),store_name=VALUES(store_name),store_url=VALUES(store_url),store_logo=VALUES(store_logo),timestamp=VALUES(timestamp);", $this->affiliate_id, $this->device_id, $this->device_id_int, $this->language, $this->price, $this->shipping, $this->currency, $this->store_name, $this->store_url, $this->store_logo, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>