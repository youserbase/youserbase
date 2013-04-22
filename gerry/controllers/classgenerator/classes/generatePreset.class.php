<?php
class generatePreset
{
	private static $class = array();
	private static $functions = array('__construct', '__clone', 'Get', 'Init', 'Load', 'Set', 'Save');
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
			self::$class[] = '	public static $'.$description['Field'].';';
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
				case 'Init':
					self::create_init($table_description);
					break;
				case 'Load':
					self::create_load();
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
		self::new_line();
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
	private function create_load()
	{
		self::$class[] = '	public function Load($'.self::$table.'_id = null)';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		self::$class[] = '		if($'.self::$table.'_id !== null)';
		self::new_line();
		self::$class[] = '		{';
		self::new_line();
		self::$class[] = '			self::Init($'.self::$table.'_id);';
		self::new_line();
		self::$class[] = '		}';
		self::new_line();
		self::$class[] = '		return self::$data;';
		self::new_line();
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
		self::$class[] = '	private function Init($'.self::$table.'_id = null)';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		self::$class[] = '		if($'.self::$table.'_id !== null)';
		self::new_line();
		self::$class[] = '		{';
		self::new_line();
		self::$class[] = '			$result = DBManager::Get(\'devices\')->query("SELECT * FROM ".self::$table." WHERE '.$id['Field'].' = ?;", $'.self::$table.'_id)->to_array();';
		self::new_line();
		self::$class[] = '			self::$data = array();';
		self::new_line();
		self::$class[] = '		}';
		self::new_line();
		self::$class[] = '		else{';
		self::new_line();
		self::$class[] = '		$result = DBManager::Get(\'devices\')->query("SELECT * FROM ".self::$table." ORDER BY ".self::$table."_name".";")->to_array();';
		self::new_line();
		self::$class[] = '		}';
		self::new_line();
		self::$class[] = '		foreach($result as $id => $line)';
		self::new_line();
		self::$class[] = '		{';
		self::new_line();
		$data = '			self::$data[$id] = array(';
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
		self::$class[] = '	public function Get()';
		self::new_line();
		self::$class[] = '	{';
		self::new_line();
		self::$class[] = '		if(self::$instance == null)';
		self::new_line();
		self::$class[] = '		{';
		self::new_line();
		self::$class[] = '			$c = __CLASS__;';
		self::new_line();
        self::$class[] = '			self::$instance = new $c;';
		self::new_line();
		self::$class[] = '			self::Init();';
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