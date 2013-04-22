<?
class device_links
{
protected $device_links_id = array("field" => "device_links_id", "type" => "int(11)", "null" => "NO", "input" => "");
protected $device_id = array("field" => "device_id", "type" => "char(32)", "null" => "NO", "input" => "");
protected $device_id_int = array("field" => "device_id_int", "type" => "int(11)", "null" => "NO", "input" => "");
protected $link = array("field" => "link", "type" => "char(250)", "null" => "NO", "input" => "");
protected $title = array("field" => "title", "type" => "char(150)", "null" => "NO", "input" => "");
protected $page_type = array("field" => "page_type", "type" => "char(150)", "null" => "NO", "input" => "");
protected $content_type = array("field" => "content_type", "type" => "char(150)", "null" => "NO", "input" => "");
protected $language_id = array("field" => "language_id", "type" => "char(2)", "null" => "NO", "input" => "");
protected $youser_id = array("field" => "youser_id", "type" => "int(11)", "null" => "NO", "input" => "");
protected $timestamp = array("field" => "timestamp", "type" => "timestamp", "null" => "NO", "input" => "");

	public function __construct($device_links_id='', $device_id='', $device_id_int='', $link='', $title='', $page_type='', $content_type='', $language_id='', $youser_id='', $timestamp='')
	{
		$this->device_links_id = $device_links_id;
		$this->device_id = $device_id;
		$this->device_id_int = $device_id_int;
		$this->link = $link;
		$this->title = $title;
		$this->page_type = $page_type;
		$this->content_type = $content_type;
		$this->language_id = $language_id;
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
				$this->youser_id = md5(uniqid("device_links", true)."/".time());
			}
			$db = DBManager::Get("devices");
			$db->query("INSERT INTO device_links (device_links_id, device_id, device_id_int, link, title, page_type, content_type, language_id, youser_id, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE device_links_id=VALUES(device_links_id), device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), link=VALUES(link), title=VALUES(title), page_type=VALUES(page_type), content_type=VALUES(content_type), language_id=VALUES(language_id), youser_id=VALUES(youser_id), timestamp=VALUES(timestamp)",$this->device_links_id, $this->device_id, $this->device_id_int, $this->link, $this->title, $this->page_type, $this->content_type, $this->language_id, $this->youser_id);
			
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