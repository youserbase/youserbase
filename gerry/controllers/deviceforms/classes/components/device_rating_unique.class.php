<?
class device_rating_unique
{
protected $device_rating_id = array("field" => "device_rating_id", "type" => "int(11)", "null" => "NO", "input" => "");
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11) unsigned", "null" => "NO", "input" => "hidden");
protected $rating = array("field" => "rating", "type" => "float(5,2)", "null" => "NO", "input" => "");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "");
protected $youser_id = array("field" => "youser_id", "type" => "int(11)", "null" => "YES", "input" => "notshown");

	public function __construct($device_rating_id='', $device_id='', $device_id_int='', $rating='', $timestamp='', $youser_id='')
	{
		$this->device_rating_id = $device_rating_id;
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->rating = $rating;
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
				$this->youser_id = md5(uniqid("device_rating_unique", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_rating_unique (device_rating_id, device_id, device_id_int, rating, timestamp, youser_id) VALUES (?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE device_rating_id=VALUES(device_rating_id), device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), rating=VALUES(rating), timestamp=VALUES(timestamp), youser_id=VALUES(youser_id)",$this->device_rating_id, $this->device_id, $this->device_id_int, $this->rating, $this->youser_id);
			
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