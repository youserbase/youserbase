<?php
class generateClasses
{
	private static $class = array();
	private static $functions = array('__construct', '__clone', 'Get', 'Loadview', 'Loadedit', 'Set', 'Save');
	private static $table;

	/**
	 * Classgenerator for Preset-Tables. Will build Singleton-Classes
	 *
	 * @param string $table The name of the Class to create
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	public function generate($table, $table_description)
	{
		self::$class = array();
		self::$table = $table;
		self::$class[] = '<?php';
		self::new_line();
		self::$class[] = 'class '.$table;
		self::new_line();
		self::$class[] = '{';
		self::new_line();
		self::create_variables($table, $table_description);
		self::create_function($table_description);
		self::$class[] = '}';
		self::new_line();
		self::$class[] = '?>';
		self::write($table);
	}

	/**
	 * Writes the created Class to Filesystem
	 *
	 * @param string $table The name of the Class to write
	 */
	private function write($table)
	{
		$path = realpath(dirname(__FILE__).'/..').'/classes/components/';
		if(($handle = fopen($path.$table.'.class.php', 'w+')) !== false)
		{
			foreach (self::$class as $line)
			{
				fwrite($handle, $line);
			}
			fclose($handle);
		}
	}

	/**
	 * Creates all variables for th class
	 *
	 * @param string $table The name of the Class to create
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_variables($table, $table_description)
	{
		self::new_line();
		self::$class[] = '	protected static $instance = null;';
		self::new_line();
		self::$class[] = '	private static $table = \''.$table.'\';';
		self::new_line();
		self::$class[] = '	public static $data = array();';
		foreach ($table_description as $description)
		{
			self::new_line();
			self::$class[] = '	public $'.$description['Field'].';';
		}
		self::new_line();
		self::new_line();
	}


	/**
	 * Manages the Creation of all Functions in the Class
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_function($table_description)
	{
		foreach (self::$functions as $function)
		{
			self::new_line();
			switch ($function)
			{
				case '__construct':
					self::create_construct();
					break;
				case 'Get':
					self::create_get($table_description);
					break;
				case 'Loadview':
					self::create_loadview($table_description);
					break;
				case 'Loadedit':
					self::create_loadedit($table_description);
					break;
				case 'Set':
					self::create_set($table_description);
					break;
				case 'Save':
					self::create_save($table_description);
					break;
				case '__clone':
					self::create_clone();
					break;
			}
			self::new_line();
		}
	}

	/**
	 * Writes \r\n
	 *
	 */
	private function new_line()
	{
		self::$class[] = "\r\n";
	}

	/**
	 * Builds the Save function
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_save($table_description)
	{
		$query = 'INSERT INTO '.self::$table.' (';
		$values = ' VALUES(';
		$onupdate = ' ON DUPLICATE KEY UPDATE ';
		$vars = ', ';
		foreach ($table_description as $description)
		{
			$values .= '?, ';
			$query .= $description['Field'].', ';
			$onupdate .= $description['Field'].'=VALUES('.$description['Field'].'),';
			$vars .= '$this->'.$description['Field'].', ';
		}
		$query .= ')';
		$values .= ')';
		$onupdate .= ';';
		$vars .= ');';
		$query = str_replace(', )', ')', $query);
		$values = str_replace(', )', ')', $values);
		$onupdate = str_replace(',;', ';', $onupdate);
		$vars = str_replace(', )', ')', $vars);
		self::$class[] = '	public function save()';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		self::$class[] = '		DBManager::Get(\'devices\')->query("LOCK TABLES '.self::$table.' WRITE;");';
		self::new_line();
		self::$class[] = '		$db = DBManager::Get(\'devices\')->query("'.$query.$values.$onupdate.'"'.$vars;
		self::new_line();
		self::$class[] = '		DBManager::Get(\'devices\')->query("UNLOCK TABLES;");';
		self::$class[] = '	}';
		self::new_line();
	}

	/**
	 * Builds the Set function
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_set($table_description)
	{
		$constructor = '	public function Set(';
		foreach ($table_description as $description)
		{
			$constructor .= '$'.$description['Field'].' = null, ';
		}
		$constructor .= ')';
		$constructor = str_replace(', )', ')', $constructor);
		self::$class[] = $constructor;
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		foreach ($table_description as $description)
		{
			self::$class[] = '		if($'.$description['Field'].' !== null)';
			self::new_line();
			self::$class[] = '		{';
			self::new_line();
			self::$class[] = '			self::$'.$description['Field'].' = $'.$description['Field'].';';
			self::new_line();
			self::$class[] = '		}';
		}
		self::new_line();
		self::$class[] = '	}';
	}

	/**
	 * Builds the Load function
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_loadview($table_description)
	{
		$id = reset($table_description);
		self::$class[] = '	public function Loadview($component_id = null, $shift = 0)';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		$join = array();
		$multiple = false;
		foreach ($table_description as $description)
		{
			$fields[$description['Comment']] = $description['Field'];
			if(preg_match('/((\w+)?(_type))(_id\b)/', $description['Field'], $match))
			{
				$join[$description['Field']] = ' LEFT JOIN '.$match[1].' ON '.self::$table.'.'.$description['Field'].' = '.$match[1].'.'.$description['Field'];
			}
			else if($description['Field'] == 'manufacturer_id')
			{
				$join[$description['Field']] = ' LEFT JOIN manufacturer ON '.self::$table.'.manufacturer_Id = manufacturer.manufacturer_id';
			}
			if ($description['Comment'] == 'multiple') {
				$multiple = true;
			}
			if($description['Comment'] != 'hidden' && $description['Comment'] != 'notshown')
			{
				self::$class[] = '		$description[\''.$description['Field'].'\'] = \''.$description['Field'].'\';';
				self::new_line();
			}
		}
		self::$class[] = '		if($component_id == null) return $description;';
		self::new_line();
		if(!empty($join))
		{
			$join[] = ' LEFT JOIN '.self::$table.' AS t ON '.self::$table.'.'.self::$table.'_id = t.'.self::$table.'_id';
		}
		$query = '"SELECT * FROM '.self::$table.' '.implode(' ', $join).' WHERE '.self::$table.'.component_id = ?';
		if(self::$table == 'market_information')
		{
			$query .= ' AND '.self::$table.'.country_id = ? ORDER BY '.self::$table.'.timestamp DESC;", $component_id, Babelfish::GetLanguage())->to_array()';
		}
		else
		{
			$query .= ' ORDER BY '.self::$table.'.timestamp DESC;", $component_id)->to_array()';
		}

		self::new_line();
		self::$class[] = '		$response = DBManager::Get(\'devices\')->query('.$query.';';
		self::new_line();
		if(self::$table == 'market_information')
		{
			self::$class[] = '		if(empty($response)) $response = DBManager::Get(\'devices\')->query("SELECT * FROM '.self::$table.' '.implode(' ', $join).' WHERE '.self::$table.'.component_id = ? AND ('.self::$table.'.country_id = \'us\' || '.self::$table.'.country_id = \'uk\') LIMIT 0,1;", $component_id)->to_array();';
		}
		self::new_line();
		self::$class[] = '		if(empty($response)) return $description;';

		self::new_line();
		self::$class[] = '		$data = array();';
		self::new_line();
		self::new_line();
		self::$class[] = '		foreach ($response as $set)
		{
			if(!isset($result[$set[\'timestamp\']]))
			{
				$result[$set[\'timestamp\']] = array($set);
			}
			else {
				array_push($result[$set[\'timestamp\']], $set);
			}
		}
		unset($response);
		$data = array();
		$result = array_splice($result, $shift, 1);';
		self::$class[] = '		foreach(reset($result) as $line => $content)';
		self::new_line();
		self::$class[] = '		{';
		self::new_line();
		self::new_line();
		self::$class[] = '			foreach($content as $line_name => $line_content)
			{
				if(strpos($line_name, \'_id\') === false && strpos($line_name, \'units\') === false && strpos($line_name, \'memory_size_type\') === false && strpos($line_name, \'currency_type_name\') === false && strpos($line_name,\'version\') === false && strpos($line_name,\'device\') === false && strpos($line_name,\'alternative\') === false && strpos($line_name,\'short\') === false && strpos($line_name,\'website\') === false)
				{
					if(preg_match(\'/((\w+)?(_type))(_name\b)/\', $line_name, $match))
					{
						if(isset($content[$match[1].\'_name\']))
						{
						';
		if(self::$table == 'operatingsystem')
		{
			self::$class[] ='	if($line_name == \'operatingsystem_type_name\')
							{
								$data[\'operatingsystem_type_id\'] = array(\'name\' => $content[str_replace(\'type_id\', \'type_name\', $line_name)], \'unit\' => $content[\'operatingsystem_type_version\']);
							}
							if(!isset($data[$match[1].\'_id\']))
							{
								$data[$match[1].\'_id\'] = array($content[$match[1].\'_name\']);
							}
							else if(!in_array($content[$match[1].\'_name\'], $data[$match[1].\'_id\']))
							{
								array_push($data[$match[1].\'_id\'], $content[$match[1].\'_name\']);
							}';
		}
		else if(self::$table == 'data_port')
		{
			self::$class[] ='	if($line_name == \'data_port_type_name\')
							{
								$data[\'data_port_type_id\'] = array(\'name\' => $content[str_replace(\'type_id\', \'type_name\', $line_name)], \'unit\' => $content[\'data_port_type_standard\']);
							}
							if(!isset($data[$match[1].\'_id\']))
							{
								$data[$match[1].\'_id\'] = array($content[$match[1].\'_name\']);
							}
							else if(!in_array($content[$match[1].\'_name\'], $data[$match[1].\'_id\']))
							{
								array_push($data[$match[1].\'_id\'], $content[$match[1].\'_name\']);
							}';
		}
		else
		{
			self::$class[] = '				if(!isset($data[$match[1].\'_id\']))
							{
								$data[$match[1].\'_id\'] = array($content[$match[1].\'_name\']);
							}
							else if(is_array($data[$match[1].\'_id\']) && !in_array($content[$match[1].\'_name\'], $data[$match[1].\'_id\']))
							{
								array_push($data[$match[1].\'_id\'], $content[$match[1].\'_name\']);
							}';
		}
		self::$class[] = '		}
						else
						{
							if(!isset($data[$match[1].\'_name\']))
							{
								$data[$match[1].\'_id\'] = $match[1].\'_id\';
							}
						}
					}
					else if($line_name == \'manufacturer_name\')
					{
						if(isset($content[\'manufacturer_name\']))
						{
							if(!isset($data[\'manufacturer_id\'])  || !is_array($data[\'manufacturer_id\']))
							{
								$data[\'manufacturer_id\'] = array($content[\'manufacturer_name\']);
							}
							else if(!in_array($content[\'manufacturer_name\'], $data[\'manufacturer_id\']))
							{
								array_push($data[\'manufacturer_id\'], $content[\'manufacturer_name\']);
							}
						}
						else
						{
							$data[\'manufacturer_id\'] = \'manufacturer_id\';
						}
					}
					else if(!isset($data[$line_name]))
					{
						if(strpos($line_name, \'length\') ||strpos($line_name, \'width\') || strpos($line_name, \'thickness\') || strpos($line_name, \'size_diagonally\') !== false && isset($content[\'size_units_type_name\']))
						{
							$data[$line_name] = array(\'value\' => $line_content, \'unit\' => $content[\'size_units_type_name\']);
						}
						else if(strpos($line_name, \'weight\'))
						{
							$data[$line_name] = array(\'value\' => $line_content, \'unit\' => $content[\'weight_units_type_name\']);
						}
						else if(strpos($line_name, \'retail_price\'))
						{
							$data[$line_name] = array(\'value\' => $line_content, \'unit\' => $content[\'currency_type_name\']);
						}
						else if(strpos($line_name, \'time\'))
						{
							$data[$line_name] = array(\'value\' => $line_content, \'unit\' => $content[\'time_units_type_name\']);
						}
						else if($line_name === \'internal_memory_size\' || $line_name === \'extendable_memory_maxsize\' || $line_name === \'ram_size\')
						{
							$data[$line_name] = array(\'value\' => $line_content, \'unit\' => $content[\'memory_size_type_name\']);
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
				if($line_name == \'youser_id\')
				{
					$data[\'youser_id\'] = $line_content;
				}
				if($line_name == \'timestamp\')
				{
					$data[\'timestamp\'] = $line_content;
				}
			}';
		self::new_line();
		self::$class[] = '		}';
		self::new_line();
		self::$class[] = '		return $data;';
		self::new_line();
		self::$class[] = '	}';
	}

	public function create_loadedit($table_description)
	{
		self::$class[] = '	public function Loadedit($component_id = null)';
		self::$class[] = self::new_line();
		self::$class[] = '	{';
		self::$class[] = self::new_line();
		$join = array();
		$multiple = false;
		foreach ($table_description as $description)
		{
			$fields[$description['Comment']] = $description['Field'];
			if(preg_match('/((\w+)?(_type))(_id\b)/', $description['Field'], $match))
			{
				$join[$description['Field']] = ' LEFT JOIN '.$match[1].' ON '.self::$table.'.'.$description['Field'].' = '.$match[1].'.'.$description['Field'];
			}
			if ($description['Comment'] == 'multiple') {
				$multiple = true;
			}
				self::$class[] = '		$description[\''.$description['Field'].'\'] = \''.$description['Comment'].'\';';
				self::new_line();
		}
		if(!empty($join))
		{
			$join[] = ' LEFT JOIN '.self::$table.' AS t ON '.self::$table.'.'.self::$table.'_id = t.'.self::$table.'_id';
		}
		$query = '"SELECT * FROM '.self::$table.' '.implode(' ', $join).' WHERE '.self::$table.'.component_id = ?';
		if(self::$table == 'market_information')
		{
			$marketquery = $query.' AND country_id = ?';
		}
		if($multiple)
		{
			$query .= ' AND '.self::$table.'.timestamp = (SELECT MAX(timestamp) FROM '.self::$table.' WHERE '.self::$table.'.component_id = ?);", $component_id, $component_id)->to_array()';
		}
		else
		{
			if(self::$table == 'market_information')
			{
				$marketquery .= ' ORDER BY '.self::$table.'.timestamp DESC LIMIT 0,1;", $component_id, Babelfish::GetLanguage())->to_array()';
			}
			$query .= ' ORDER BY '.self::$table.'.timestamp DESC LIMIT 0,1;", $component_id)->to_array()';
		}
		self::new_line();
		self::$class[] = '		$data = $description;';
		self::new_line();
		if(self::$table == 'market_information')
		{
			self::$class[] = '		$result = DBManager::Get(\'devices\')->query('.$query.';';
			self::new_line();
			self::$class[] = '		if($result === null){
			$result = DBManager::Get(\'devices\')->query('.$marketquery.';
			}';
		}
		else
		{
			self::$class[] = '		$result = DBManager::Get(\'devices\')->query('.$query.';';
			self::new_line();
		}
		self::$class[] = '		if($result !== null){
			$data = array();
		';
		self::new_line();
		self::$class[] = '			foreach($result as $line => $content)
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
					if($line_name == \'youser_id\')
					{
						$data[\'youser_id\'] = $line_content;
					}
					if($line_name == \'timestamp\')
					{
						$data[\'timestamp\'] = $line_content;
					}
				}
			}
		}
		$preset = array();
		foreach($description as $line_name => $line_type)
		{
			if(($line_type == \'select\' || $line_type == \'multiple\' || strpos($line_name, \'type_id\')) && strpos($line_name, \'type_id_int\') === false)
			{
				$table = str_replace(\'_id\', \'\', $line_name);
				$object = call_user_func_array(array($table, \'Get\'), array());
				$preset[$line_name] = $object->Load();
			}
		}
		return array(self::$table => array(\'data\' => $data, \'preset\' => $preset, \'description\' => $description));';
		self::$class[] = self::new_line();
		self::$class[] = '	}';
	}

	/**
	 * Builds the Init function
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_init($table_description)
	{
		$id =	reset($table_description);
		self::$class[] = '	private function Init()';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		$data = '		self::$data[$id] = array(';
		foreach ($table_description as $description)
		{
			$data .= '\''.$description['Field'].'\' => $line[\''.$description['Field'].'\'], ';
		}
		$data .= ');';
		self::$class[] = $data;
		self::new_line();

		self::$class[] = '		}';
		self::new_line();
		self::$class[] = '	}';
	}

	/**
	 * Builds the Get function
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_get($table_description)
	{
		$id =	reset($table_description);
		self::$class[] = '	public function Get($'.$id['Field'].' = null)';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		self::$class[] = '		if($'.$id['Field'].' != null)';
		self::new_line();
		self::$class[] = '		{';
		self::new_line();
		self::$class[] = '			self::$'.$id['Field'].' = $'.$id['Field'].';';
		self::new_line();
		self::$class[] = '		}';
		self::new_line();
		self::$class[] = '		if(self::$instance == null)';
		self::new_line();
		self::$class[] = '		{';
		self::new_line();
		self::$class[] = '			$c = __CLASS__;';
		self::new_line();
        self::$class[] = '			self::$instance = new $c;';
		self::new_line();
		self::$class[] = '		}';
		self::new_line();
		self::$class[] = '		return self::$instance;';
		self::new_line();
		self::$class[] = '	}';
		self::new_line();
	}

	/**
	 * Builds the __construct function
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_construct()
	{
		self::$class[] = '	public function __construct()';
		self::new_line();
		self::$class[] = "	{";
		self::new_line();
		self::$class[] = "	}";
		self::new_line();
	}

	/**
	 * Builds the __clone function
	 *
	 * @param array $table_description The fields, field_types and comments of the represented DB-Table
	 */
	private function create_clone()
	{
		self::$class[] = '	public function __clone()';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		self::$class[] = '		throw new Exception(\'Cannot duplicate a singleton.\');';
		self::new_line();
		self::$class[] = '	}';
		self::new_line();
	}
}
?>