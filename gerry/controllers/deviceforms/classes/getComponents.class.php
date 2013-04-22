<?

class getComponents
{
	public function getAllComponents()
	{
		$db = DBManager::Get("devices");
		$result = $db->query("SELECT component_id, components_name FROM `components`");
		$components = $this->extractResults($result);
		$result->release();
		return $components;
	}
	
	public function getAllEquipment()
	{
		$db = DBManager::Get("devices");
		$result = $db->query("SELECT equipment_id, equipment_name FROM equipment");
		$equipment = $this->extractResults($result);
		$result->release();
		return $equipment;
	}
	
	public function getAllFunctions()
	{
		$db = DBManager::Get("devices");
		$result = $db->query("SELECT function_id, function_name FROM functions");
		$functions = $this->extractResults($result);
		$result->release();
		return $functions;
	}
	
	private function extractResults($result)
	{
		$components = array();
		if(!$result->is_empty())
		{
			while ($data = $result->fetchArray())
			{
				foreach($data as $key => $value)
				{
					$components[$key][] = $value;
				}
			}
		}
		return $components;
	}
}
?>