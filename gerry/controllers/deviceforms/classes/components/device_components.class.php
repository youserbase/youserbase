<?
class device_components
{
protected $device_component_id = array("field" => "device_component_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11) unsigned", "null" => "YES", "input" => "");
protected $component_id = array("field" => "component_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $component_id_int = array("field" => "component_id_int", "type" => "bigint(20) unsigned", "null" => "NO", "input" => "hidden");
protected $table_name = array("field" => "table_name", "type" => "char(100)", "null" => "NO", "input" => "");
protected $confirmed = array("field" => "confirmed", "type" => "set('yes','no')", "null" => "NO", "input" => "notshown");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "notshown");
protected $youser_id = array("field" => "youser_id", "type" => "int(11) unsigned", "null" => "NO", "input" => "notshown");

	public function __construct($device_component_id='', $device_id='', $device_id_int='', $component_id='', $component_id_int='', $table_name='', $confirmed='', $timestamp='', $youser_id='')
	{
		$this->device_component_id = $device_component_id;
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->component_id = $component_id;
		$this->component_id_int = $component_id_int;
		$this->table_name = $table_name;
		$this->confirmed = $confirmed;
		$this->timestamp = $timestamp;
		$this->youser_id = $youser_id;
	}

	
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->youser_id===null)
			{
				$this->youser_id = md5(uniqid("device_components", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_components (device_component_id, device_id, device_id_int, component_id, component_id_int, table_name, confirmed, timestamp, youser_id) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE device_component_id=VALUES(device_component_id), device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), component_id=VALUES(component_id), component_id_int=VALUES(component_id_int), table_name=VALUES(table_name), confirmed=VALUES(confirmed), timestamp=VALUES(timestamp), youser_id=VALUES(youser_id)",$this->device_component_id, $this->device_id, $this->device_id_int, $this->component_id, $this->component_id_int, $this->table_name, $this->confirmed, $this->youser_id);
			
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