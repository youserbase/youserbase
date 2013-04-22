<?
class device_similarity
{
protected $similarity_id = array("field" => "similarity_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11) unsigned", "null" => "NO", "input" => "hidden");
protected $compared_id = array("field" => "compared_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $similarity = array("field" => "similarity", "type" => "float(5,2)", "null" => "NO", "input" => "");

	public function __construct($similarity_id='', $device_id='', $device_id_int='', $compared_id='', $similarity='')
	{
		$this->similarity_id = $similarity_id;
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->compared_id = $compared_id;
		$this->similarity = $similarity;
	}

	
	/**
	*saves the object to DB
	*
	*/
	public function save()
	{
			if($this->compared_id===null)
			{
				$this->compared_id = md5(uniqid("device_similarity", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_similarity (similarity_id, device_id, device_id_int, compared_id, similarity) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE similarity_id=VALUES(similarity_id), device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), compared_id=VALUES(compared_id), similarity=VALUES(similarity)",$this->similarity_id, $this->device_id, $this->device_id_int, $this->compared_id, $this->similarity);
			
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