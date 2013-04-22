<?php
class deviceinformation
{
	public function getAllManufacturers()
	{
		$db = DBManager::Get('devices');
		$result = $db->query("SELECT manufacturer_id, manufacturer_name FROM manufacturer");
		$manufacturer = array();
		if(!$result->is_empty())
		{
			while ($data = $result->fetch_array())
			{
				$manufacturer[$data['manufacturer_id']] = $data['manufacturer_name'];
			}
			$result->release();
		}
		return $manufacturer;
	}
	
	public function getCountries()
	{
		$db = DBManager::Get('devices');
		$result = $db->query("SELECT country_id, country_name FROM country");
		$countries = array();
		if(!$result->is_empty())
		{
			while ($data = $result->fetch_array())
			{
				$countries[$data['country_id']] = $data['country_name'];
			}
			$result->release();
		}
		return $countries;
	}
	
	public function getAllDeviceTypes()
	{
		$db = DBManager::Get('devices');
		$result = $db->query('SELECT * FROM device_type');
		$device_types = array();
		if(!$result->is_empty())
		{
			while ($content = $result->fetchArray())
			{
				$device_types[] = $content['device_type_name'];
			}
		}
		$result->release();
		return $device_types;
	}
}
?>