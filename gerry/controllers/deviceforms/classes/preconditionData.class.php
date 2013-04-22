<?
class preconditionData
{
	
	private $replacement = array('.', '-', ',', ' ', '?', '!', '/', '\\', '+', '(', ')', '[', ']', '{', '}', '\"', '$', '§', '%', '=');
	
	public function getPreconditionsSpecs($tablename)
	{
		$object = new $tablename();
		$precondition = $object->toArray();
		return $precondition;
	}
	
	public function getPreconditionsData($table)
	{
		
		$db = DBManager::Get('devices');
		$query = ("SELECT * FROM $table ");
		$device_type = null;
		if($table == 'body_type')
		{
			if(isset($_POST['main_type']))
			{
				$device_type = $_POST['main_type'];
				$query .= "WHERE device_type LIKE ?";
			}
		}
		$query .= "ORDER BY ".$table."_name";
		$query .= ';';
		$result = $db->query($query, $device_type);
		$preconditions = array();
		if (!$result->is_empty())
		{
			while ($data = $result->fetch_array())
			{
					$preconditions[] = $data;
			}
		}
		return $preconditions;
	}
	
	public function setPreconditions($tablename)
	{
		$object = new $tablename();
		$id_name = $tablename.'_id';
		if(isset($_REQUEST[$id_name]))
		{
			$id = $_REQUEST[$id_name];
		}
		else
			$id = md5(uniqid($tablename.time(true)));
		foreach ($_POST as $key => $value)
		{
			$object->$key = utf8_encode(strtoupper(str_replace($this->replacement, '', $value)));
		}
		$object->$id_name = $id;
		$object->save();
	}
}
?>
