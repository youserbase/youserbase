<?php
class getManufacturer
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
}
?>