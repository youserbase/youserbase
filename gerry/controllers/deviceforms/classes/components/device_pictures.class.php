<?
class device_pictures
{
protected $device_pictures_id = array("field" => "device_pictures_id", "type" => "int(11)", "null" => "NO", "input" => "");
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "hidden");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11) unsigned", "null" => "NO", "input" => "hidden");
protected $master_image = array("field" => "master_image", "type" => "enum('yes','no')", "null" => "NO", "input" => "hidden");
protected $device_pictures_type = array("field" => "device_pictures_type", "type" => "enum('official','accessory','user','screenshots','showcase')", "null" => "NO", "input" => "hidden");
protected $angle = array("field" => "angle", "type" => "enum('front','back','left','right','multi')", "null" => "YES", "input" => "hidden");
protected $situation = array("field" => "situation", "type" => "enum('closed','open')", "null" => "YES", "input" => "hidden");
protected $position = array("field" => "position", "type" => "int(11)", "null" => "YES", "input" => "");
protected $original_filename = array("field" => "original_filename", "type" => "varchar(255)", "null" => "NO", "input" => "hidden");
protected $youser_id = array("field" => "youser_id", "type" => "int(11) unsigned", "null" => "NO", "input" => "notshown");
protected $timestamp = array("field" => "timestamp", "type" => "datetime", "null" => "NO", "input" => "notshown");

	public function __construct($device_pictures_id='', $device_id='', $device_id_int='', $master_image='', $device_pictures_type='', $angle='', $situation='', $position='', $original_filename='', $youser_id='', $timestamp='')
	{
		$this->device_pictures_id = $device_pictures_id;
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->master_image = $master_image;
		$this->device_pictures_type = $device_pictures_type;
		$this->angle = $angle;
		$this->situation = $situation;
		$this->position = $position;
		$this->original_filename = $original_filename;
		$this->youser_id = $youser_id;
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
				$this->youser_id = md5(uniqid("device_pictures", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_pictures (device_pictures_id, device_id, device_id_int, master_image, device_pictures_type, angle, situation, position, original_filename, youser_id, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE device_pictures_id=VALUES(device_pictures_id), device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), master_image=VALUES(master_image), device_pictures_type=VALUES(device_pictures_type), angle=VALUES(angle), situation=VALUES(situation), position=VALUES(position), original_filename=VALUES(original_filename), youser_id=VALUES(youser_id), timestamp=VALUES(timestamp)",$this->device_pictures_id, $this->device_id, $this->device_id_int, $this->master_image, $this->device_pictures_type, $this->angle, $this->situation, $this->position, $this->original_filename, $this->youser_id);
			
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