<?
class device_statistics_tmp
{
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11)", "null" => "YES", "input" => "");
protected $impact = array("field" => "impact", "type" => "float(11,5)", "null" => "YES", "input" => "");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "");
protected $language = array("field" => "language", "type" => "char(2)", "null" => "YES", "input" => "");

	public function __construct($device_id='', $device_id_int='', $impact='', $timestamp='', $language='')
	{
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->impact = $impact;
		$this->timestamp = $timestamp;
		$this->language = $language;
	}

	
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->device_id_int===null)
			{
				$this->device_id_int = md5(uniqid("device_statistics_tmp", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_statistics_tmp (device_id, device_id_int, impact, timestamp, language) VALUES (?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), impact=VALUES(impact), timestamp=VALUES(timestamp), language=VALUES(language)",$this->device_id, $this->device_id_int, $this->impact, $this->language);
			
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