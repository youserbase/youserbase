<?
class device_statistics
{
protected $device_statistics_id = array("field" => "device_statistics_id", "type" => "bigint(11)", "null" => "NO", "input" => "hidden");
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "hidden");
protected $device_id_int = array("field" => "device_id_int", "type" => "bigint(20)", "null" => "YES", "input" => "");
protected $youser_id = array("field" => "youser_id", "type" => "char(32)", "null" => "YES", "input" => "");
protected $tab = array("field" => "tab", "type" => "char(40)", "null" => "NO", "input" => "");
protected $language = array("field" => "language", "type" => "char(2)", "null" => "YES", "input" => "");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "hidden");
protected $agent = array("field" => "agent", "type" => "text", "null" => "YES", "input" => "");

	public function __construct($device_statistics_id='', $device_id='', $device_id_int='', $youser_id='', $tab='', $language='', $timestamp='', $agent='')
	{
		$this->device_statistics_id = $device_statistics_id;
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->youser_id = $youser_id;
		$this->tab = $tab;
		$this->language = $language;
		$this->timestamp = $timestamp;
		$this->agent = $agent;
	}

	
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->youser_id===null)
			{
				$this->youser_id = md5(uniqid("device_statistics", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_statistics (device_statistics_id, device_id, device_id_int, youser_id, tab, language, timestamp, agent) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE device_statistics_id=VALUES(device_statistics_id), device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), youser_id=VALUES(youser_id), tab=VALUES(tab), language=VALUES(language), timestamp=VALUES(timestamp), agent=VALUES(agent)",$this->device_statistics_id, $this->device_id, $this->device_id_int, $this->youser_id, $this->tab, $this->language, $this->agent);
			
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