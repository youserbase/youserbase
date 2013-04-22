<?
class device_links_helpfull
{
protected $device_links_helpfull_id = array("field" => "device_links_helpfull_id", "type" => "int(11)", "null" => "NO", "input" => "");
protected $device_links_id = array("field" => "device_links_id", "type" => "int(11)", "null" => "YES", "input" => "");
protected $youser_id = array("field" => "youser_id", "type" => "int(11)", "null" => "YES", "input" => "");
protected $helpfull = array("field" => "helpfull", "type" => "int(11)", "null" => "YES", "input" => "");
protected $not_helpfull = array("field" => "not_helpfull", "type" => "int(11)", "null" => "YES", "input" => "");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "");

	public function __construct($device_links_helpfull_id='', $device_links_id='', $youser_id='', $helpfull='', $not_helpfull='', $timestamp='')
	{
		$this->device_links_helpfull_id = $device_links_helpfull_id;
		$this->device_links_id = $device_links_id;
		$this->youser_id = $youser_id;
		$this->helpfull = $helpfull;
		$this->not_helpfull = $not_helpfull;
		$this->timestamp = $timestamp;
	}

	
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->youser_id===null)
			{
				$this->youser_id = md5(uniqid("device_links_helpfull", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_links_helpfull (device_links_helpfull_id, device_links_id, youser_id, helpfull, not_helpfull, timestamp) VALUES (?, ?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE device_links_helpfull_id=VALUES(device_links_helpfull_id), device_links_id=VALUES(device_links_id), youser_id=VALUES(youser_id), helpfull=VALUES(helpfull), not_helpfull=VALUES(not_helpfull), timestamp=VALUES(timestamp)",$this->device_links_helpfull_id, $this->device_links_id, $this->youser_id, $this->helpfull, $this->not_helpfull);
			
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