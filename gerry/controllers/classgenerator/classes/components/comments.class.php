<?php
class comments
{

	protected static $instance = null;
	private static $table = 'comments';
	public static $data = array();
	public $comments_id;
	public $device_id;
	public $category;
	public $comment;
	public $type;
	public $videos_id;
	public $website;
	public $email;
	public $language;
	public $negative;
	public $positive;
	public $offensive_counts;
	public $youser_id;
	public $timestamp;


	public function __construct()
	{
	}


	public function __clone()
	{
		throw new Exception('Cannot duplicate a singleton.');
	}


	public function Get($comments_id = null)
	{
		if($comments_id != null)
		{
			self::$comments_id = $comments_id;
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
		$description['comments_id'] = 'comments_id';
		$description['device_id'] = 'device_id';
		$description['category'] = 'category';
		$description['comment'] = 'comment';
		$description['type'] = 'type';
		$description['videos_id'] = 'videos_id';
		$description['website'] = 'website';
		$description['email'] = 'email';
		$description['language'] = 'language';
		$description['negative'] = 'negative';
		$description['positive'] = 'positive';
		$description['offensive_counts'] = 'offensive_counts';
		$description['youser_id'] = 'youser_id';
		$description['timestamp'] = 'timestamp';
		if($component_id == null) return $description;

		$response = DBManager::Get('devices')->query("SELECT * FROM comments  WHERE comments.component_id = ? ORDER BY comments.timestamp DESC;", $component_id)->to_array();

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
		$description['comments_id'] = '';
		$description['device_id'] = '';
		$description['category'] = '';
		$description['comment'] = '';
		$description['type'] = '';
		$description['videos_id'] = '';
		$description['website'] = '';
		$description['email'] = '';
		$description['language'] = '';
		$description['negative'] = '';
		$description['positive'] = '';
		$description['offensive_counts'] = '';
		$description['youser_id'] = '';
		$description['timestamp'] = '';

		$data = $description;
		$result = DBManager::Get('devices')->query("SELECT * FROM comments  WHERE comments.component_id = ? ORDER BY comments.timestamp DESC LIMIT 0,1;", $component_id)->to_array();
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

	public function Set($comments_id = null, $device_id = null, $category = null, $comment = null, $type = null, $videos_id = null, $website = null, $email = null, $language = null, $negative = null, $positive = null, $offensive_counts = null, $youser_id = null, $timestamp = null)
	{
		if($comments_id !== null)
		{
			self::$comments_id = $comments_id;
		}		if($device_id !== null)
		{
			self::$device_id = $device_id;
		}		if($category !== null)
		{
			self::$category = $category;
		}		if($comment !== null)
		{
			self::$comment = $comment;
		}		if($type !== null)
		{
			self::$type = $type;
		}		if($videos_id !== null)
		{
			self::$videos_id = $videos_id;
		}		if($website !== null)
		{
			self::$website = $website;
		}		if($email !== null)
		{
			self::$email = $email;
		}		if($language !== null)
		{
			self::$language = $language;
		}		if($negative !== null)
		{
			self::$negative = $negative;
		}		if($positive !== null)
		{
			self::$positive = $positive;
		}		if($offensive_counts !== null)
		{
			self::$offensive_counts = $offensive_counts;
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
		DBManager::Get('devices')->query("LOCK TABLES comments WRITE;");
		$db = DBManager::Get('devices')->query("INSERT INTO comments (comments_id, device_id, category, comment, type, videos_id, website, email, language, negative, positive, offensive_counts, youser_id, timestamp) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE comments_id=VALUES(comments_id),device_id=VALUES(device_id),category=VALUES(category),comment=VALUES(comment),type=VALUES(type),videos_id=VALUES(videos_id),website=VALUES(website),email=VALUES(email),language=VALUES(language),negative=VALUES(negative),positive=VALUES(positive),offensive_counts=VALUES(offensive_counts),youser_id=VALUES(youser_id),timestamp=VALUES(timestamp);", $this->comments_id, $this->device_id, $this->category, $this->comment, $this->type, $this->videos_id, $this->website, $this->email, $this->language, $this->negative, $this->positive, $this->offensive_counts, $this->youser_id, $this->timestamp);
		DBManager::Get('devices')->query("UNLOCK TABLES;");	}

}
?>