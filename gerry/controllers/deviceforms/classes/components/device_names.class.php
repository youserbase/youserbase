<?
class device_names
{
protected $device_names_id = array("field" => "device_names_id", "type" => "char(32)", "null" => "NO", "input" => "hidden");
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11) unsigned", "null" => "NO", "input" => "hidden");
protected $manufacturer_id = array("field" => "manufacturer_id", "type" => "char(32)", "null" => "NO", "input" => "select");
protected $device_names_name = array("field" => "device_names_name", "type" => "char(50)", "null" => "NO", "input" => "text");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "notshown");
protected $youser_id = array("field" => "youser_id", "type" => "int(11) unsigned", "null" => "NO", "input" => "notshown");

	public function __construct($device_names_id='', $device_id='', $device_id_int='', $manufacturer_id='', $device_names_name='', $timestamp='', $youser_id='')
	{
		$this->device_names_id = $device_names_id;
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->manufacturer_id = $manufacturer_id;
		$this->device_names_name = $device_names_name;
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
				$this->youser_id = md5(uniqid("device_names", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_names (device_names_id, device_id, device_id_int, manufacturer_id, device_names_name, timestamp, youser_id) VALUES (?, ?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE device_names_id=VALUES(device_names_id), device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), manufacturer_id=VALUES(manufacturer_id), device_names_name=VALUES(device_names_name), timestamp=VALUES(timestamp), youser_id=VALUES(youser_id)",$this->device_names_id, $this->device_id, $this->device_id_int, $this->manufacturer_id, $this->device_names_name, $this->youser_id);
			
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