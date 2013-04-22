<?

class designDevices
{

	public function setDeviceDefinition()
	{
		$device_type_name = new device_type($_POST['device_type_name'], $_POST['device_type_class']);
		$device_type_name->save();
		foreach($_POST as $key => $post)
		{
			if($key == 'components')
			{
				foreach ($post as $key => $table)
				{
					$device_design = new device_design(md5(uniqid($table.$key, true)."/".time()), $_POST['device_type_name'], $table, $device_type_id);
					$device_design->save(); 
				}
			}
		}
	}
	
	public function getAllComponentsFromDeviceType($types='')
	{
		$tables = array();
		if(isset($_POST['device_types']))
		{
			$db = DBManager::Get('devices');
			$result = $db->query("SELECT table_name FROM device_design WHERE `device_type_id` LIKE ? ;",$_POST['device_types']);
			if(!$result->is_empty())
			{
				while ($content = $result->fetchArray())
				{
					$tables[] = $content['table_name'];
				}
			}
			$result->release();
		}
		else if (isset($types))
		{
			$db = DBManager::Get('devices');
			if (is_array($types))
			{
				foreach ($types as $type)
				{
					$result = $db->query("SELECT table_name FROM device_design WHERE `device_type` LIKE ? ;",$type);
					if(!$result->is_empty())
					{
						while ($content = $result->fetchArray())
						{
							$tables[] = $content['table_name'];
						}
					}
					$result->release();
				}
			}
			else 
			{
				$result = $db->query("SELECT table_name FROM device_design WHERE `device_type` LIKE ? ;",$types);
				if(!$result->is_empty())
				{
					while ($content = $result->fetchArray())
					{
						$tables[] = $content['table_name'];
					}
				}
				$result->release();
			}
		}
		return $tables;
	}
}

?>