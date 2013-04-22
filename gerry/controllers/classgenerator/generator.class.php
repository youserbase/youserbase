<?

class generator extends Controller
{
	private $id;
	
	public function __construct()
	{
		
	}
	
	public function initGenerator()
	{
		$template = $this->get_template(true);
		if (isset($_POST['start']))
		{
			$count = $this->function_write_file();
			$template->assign('status', $count);
		}
		if(isset($_REQUEST['deleted']))
		{
			$template->assign('deleted', $_REQUEST['deleted']);
		}
		
	}
	
	public function function_write_file($table)
	{
		$tables = $this->extractResults($this->show_tables());
		$count = 0;
			if(strpos($table, '_type') == false && $table != 'manufacturer')
			{
				$vars = $this->extractResults($this->describe_table($table));
				$this->create_file($vars, $table);
				$count++;
			}
		return $count.' Klassen erzeugt';
	}


	private function extractResults($result)
	{
		$extract = array();
		if(!$result->is_empty())
		{
			while($row = $result->fetchArray())
			{
				$extract[] = $row;
			}
		}
		return $extract;
	}

	private function show_tables()
	{
		$query = "SHOW TABLES;";
		$db = DBManager::Get('devices');
		$tables = $db->query($query);
		return $tables;
	}

	private function describe_table($table)
	{
		$query = "SHOW FULL COLUMNS FROM `$table;";
		$db = DBManager::Get('devices');
		$table = $db->query($query);
		return $table;
	}

	private function create_file($vars, $table)
	{
		$field = array();
		$qm = array();
		$values = array();
		$onUpdate = array();
		foreach($vars as $var)
		{
			$field[] = $var['Field'];
			$onUpdate[] = $var['Field'].'=VALUES('.$var['Field'].')';
			if($var['Field'] != 'timestamp')
			{
				$qm[] = '?';
				$values[] = '$this->'.$var['Field'];
			}
			else
			{ 
				$qm[] ='NOW()';
			}
		}
		$class = '';
		$path = realpath(dirname(__FILE__).'/..').'/deviceforms/classes/components/';
		
		$file = $path.$table.'.class.php';

		
		$class = "<?
class $table
{
";
			foreach($vars as $var)
			{
				$class .= 'protected $'.$var['Field'].' = array("field" => "'.$var['Field'].'", "type" => "'.$var['Type'].'", "null" => "'.$var['Null'].'", "input" => "'.$var['Comment'].'");
';
				if(strpos($var['Field'],'_id')!==false)
				{
					$this->id = $var['Field'];
				}
			}
			$class .= "
	public function __construct(";
			foreach($vars as $var)
			{
				$class .= '$'.$var['Field']."=''";
				if(next($vars)) $class .= ', ';
			}
		$class .= ")
	{
";
			foreach($vars as $var)
			{
				$class .= '		$this->'.$var['Field'].' = $'.$var['Field'].';
';
			}
$class .= '	}

	';


	$class .= '
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->'.$this->id.'===null)
			{
				$this->'.$this->id.' = md5(uniqid("'.$table.'", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO '.$table.' (';
			$class .= implode(', ', $field);	
			$class .= ') VALUES (';
			$class .= implode(', ', $qm);
			$class .= ') ON DUPLICATE KEY UPDATE ';
			$class .= implode(', ', $onUpdate);
			$class .= '",';
			$class .= implode(', ', $values);
			$class .= ');
			
			return $db->affected_rows()>0;
	}
	
	';
		

		$class .= '/**
	 * get value of variable named = property
	 *
	 * @param var_name $property
	 * @return value of $property
	 */
	 public function __get($property)
	{
		if(isset($this->$property))
		{
			return $this->$property;
		}
		else return null;
	}

	/**
	 * set value of variable named = property
	 *
	 * @param var_name $property
	 * @param $value of $property
	 */
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

	/**
	 * Turns the variables of the Object into / separated String
	 *
	 * @return string containing /-separated Object-varialbles
	 */
	public function toArray()
	{
		';
		$class .= '$string = array();
	    foreach (get_class_vars(get_class($this)) as $name => $value) 
	    {
	    	$string[$name] = $value;
	    }
	    return $string';
		
		$class .= ';
	}';
		
		$class .= '
	
	public function __toArray()
	{
		';
		$class .= '$string = array();
	    foreach (get_class_vars(get_class($this)) as $name => $value) 
	    {
	    	$string[$name] = $this->$name;
	    }
	    return $string';
		
		$class .= ';
	}';
		
		$class .=' 
	
	public function __toString()
	{
		';
		$class .= '$string = "";
	    foreach (get_class_vars(get_class($this)) as $name => $value) 
	    {
	    	$string .= " / ".$name."-".$this->$name;
	    }
	    return $string';
		$class .= ';
	}
}
?>';
		
		if(file_exists($file))
		{
			echo 'deleted '.$file.'</br>';
			unlink($file);
		}
		if($data = fopen($file, 'w+'))
		{
			fwrite($data, $class);
		}
	}


}

?>