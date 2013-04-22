<?
class deleted_devices
{
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11) unsigned", "null" => "NO", "input" => "hidden");
protected $device_name = array("field" => "device_name", "type" => "char(255)", "null" => "YES", "input" => "");
protected $ean = array("field" => "ean", "type" => "char(100)", "null" => "NO", "input" => "");
protected $confirmed = array("field" => "confirmed", "type" => "set('yes','no')", "null" => "NO", "input" => "");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "notshown");
protected $youser_id = array("field" => "youser_id", "type" => "int(11) unsigned", "null" => "NO", "input" => "notshown");

	public function __construct($device_id='', $device_id_int='', $device_name='', $ean='', $confirmed='', $timestamp='', $youser_id='')
	{
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->device_name = $device_name;
		$this->ean = $ean;
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
				$this->youser_id = md5(uniqid("deleted_devices", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO deleted_devices (device_id, device_id_int, device_name, ean, confirmed, timestamp, youser_id) VALUES (?, ?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), device_name=VALUES(device_name), ean=VALUES(ean), confirmed=VALUES(confirmed), timestamp=VALUES(timestamp), youser_id=VALUES(youser_id)",$this->device_id, $this->device_id_int, $this->device_name, $this->ean, $this->confirmed, $this->youser_id);
			
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