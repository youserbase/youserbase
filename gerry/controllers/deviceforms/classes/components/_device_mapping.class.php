<?
class _device_mapping
{
protected $device_id_char = array("field" => "device_id_char", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11) unsigned", "null" => "NO", "input" => "hidden");

	public function __construct($device_id_char='', $device_id_int='')
	{
		$this->device_id_char = $device_id_char;
		$this->device_id_int = $device_id_int;
	}

	
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->device_id_int===null)
			{
				$this->device_id_int = md5(uniqid("_device_mapping", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO _device_mapping (device_id_char, device_id_int) VALUES (?, ?) ON DUPLICATE KEY UPDATE device_id_char=VALUES(device_id_char), device_id_int=VALUES(device_id_int)",$this->device_id_char, $this->device_id_int);
			
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