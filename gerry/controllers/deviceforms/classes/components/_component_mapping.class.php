<?
class _component_mapping
{
protected $component_id_char = array("field" => "component_id_char", "type" => "char(32)", "null" => "NO", "input" => "");
protected $component_id_int = array("field" => "component_id_int", "type" => "bigint(20) unsigned", "null" => "NO", "input" => "hidden");

	public function __construct($component_id_char='', $component_id_int='')
	{
		$this->component_id_char = $component_id_char;
		$this->component_id_int = $component_id_int;
	}

	
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->component_id_int===null)
			{
				$this->component_id_int = md5(uniqid("_component_mapping", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO _component_mapping (component_id_char, component_id_int) VALUES (?, ?) ON DUPLICATE KEY UPDATE component_id_char=VALUES(component_id_char), component_id_int=VALUES(component_id_int)",$this->component_id_char, $this->component_id_int);
			
			return $db->affected_rows()>0;
	}
	
	/**
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
		$string = array();
	    foreach (get_class_vars(get_class($this)) as $name => $value) 
	    {
	    	$string[$name] = $value;
	    }
	    return $string;
	}
	
	public function __toArray()
	{
		$string = array();
	    foreach (get_class_vars(get_class($this)) as $name => $value) 
	    {
	    	$string[$name] = $this->$name;
	    }
	    return $string;
	} 
	
	public function __toString()
	{
		$string = "";
	    foreach (get_class_vars(get_class($this)) as $name => $value) 
	    {
	    	$string .= " / ".$name."-".$this->$name;
	    }
	    return $string;
	}
}
?>