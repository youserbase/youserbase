<?

class alterComponents
{
	public function alterComponent($template = '')
	{
		if(isset($_POST['components']))
		{
			$componentid = $_POST['components'];
			$db = DBManager::Get('devices');
			$result = $db->query("SELECT components_name FROM components WHERE component_id LIKE '$componentid'");
			if(!$result->is_empty())
			{
				while ($data = $result->fetchArray())
				{
					$table = $data['components_name'];
				}
			}
			$result->release();
			$result = $db->query("SELECT * FROM $table");
			$content = array();
			if(!$result->is_empty())
			{
				while ($data = $result->fetchArray())
				{
					foreach($data as $key => $value)
					{
						$content[$key] = $value;
					}
				}
				$result->release();
				$template->assign('table', $table);
				return $content;
			}
		}
		return false;
	}
	
	public function alterFunctions()
	{
		$db = DBManager::Get('devices');
		$result = $db->query();
	}
	
	public function alterEquipment()
	{
		$db = DBManager::Get('devices');
		$result = $db->query();
	}
}


?>