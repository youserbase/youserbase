<?php
class investigator
{
	public static function getInfoForDeviceByID($device_type)
	{
		return DBManager::Get('devices')->query("SELECT table_name FROM device_design WHERE device_type_id=?",
			$device_type
		)->to_array(null, 'table_name');
	}

	public static function getInfoForDeviceByName($device_name, $manufacturer_name)
	{
		return DBManager::Get('devices')->query("SELECT device_id FROM device_names WHERE device_names_name=? AND manufacturer_id=?",
			$device_name,
			self::getManufacturerIdByName($manufacturer_name)
		)->fetch_item();
	}

	public static function getdeviceid($device_name, $manufacturer_id)
	{
		return DBManager::Get('devices')->query("SELECT device_id FROM device_names WHERE device_names_name=? AND manufacturer_id=?",
			$device_name,
			$manufacturer_id
		)->fetch_item();
	}

	public static function getManufacturerIdByName($name)
	{
		return DBManager::Get('devices')->query("SELECT manufacturer_id FROM manufacturer WHERE manufacturer_name=?",
			$name
		)->fetch_item();
	}

	public static function GetManufacturerName($id)
	{
		return DBManager::Get('devices')->query("SELECT manufacturer_name FROM manufacturer WHERE manufacturer_id=?",
			$id
		)->fetch_item();
	}

	public static function getTablesByID($device_id)
	{
		return  DBManager::Get('devices')->query("SELECT table_name, component_id FROM device_components WHERE device_id=?", $device_id)->to_array('component_id', 'table_name');
	}

	public static function getComponentsByID($tables)
	{
		$values = false;
		$db = DBManager::Get('devices');
		if(is_array($tables))
		{
			foreach ($tables as $component_id => $table)
			{
				if($table == 'market_information')
				{
					$result = $db->limit(1)->query("SELECT * FROM $table WHERE component_id = ?  AND country_id = ? ORDER BY timestamp DESC", $component_id, BabelFish::GetLanguage());
					if($result->is_empty())
					{
						$result = $db->limit(1)->query("SELECT * FROM {$table} WHERE component_id = ? ORDER BY timestamp DESC", $component_id);
					}
				}
				else
				{
					$result = $db->query("SELECT * FROM $table WHERE component_id = ? AND timestamp = (SELECT MAX(timestamp) FROM $table WHERE component_id = ?)", $component_id, $component_id);
				}
//				Not functional by maunsen
//				$result = $db->limit(1)->query("SELECT * FROM {$table} WHERE component_id = ? ORDER BY timestamp DESC", $component_id);
				while ($data = $result->fetch_array())
				{
					foreach($data as $key => $value)
					{
						if(!isset($values[$table.'_type'][$key]))
						{
							 $values[$table.'_type'][$key] = array($value);
						}
						else if(!in_array($value, $values[$table.'_type'][$key]))
						{
							array_push($values[$table.'_type'][$key], $value);
						}
					}
				}
				$result->release();
			}
			return $values;
		}
	}

	public static function getDeviceTypesByClass($class)
	{
		return DBManager::Get('devices')->query("SELECT device_type_name, device_type_class FROM device_type WHERE device_type_class=?",
			$class
		)->to_array('device_type_name', 'device_type_class');
	}

	public static function getDeviceTypesByDeviceID($device_id)
	{
		$device_types = false;
		return DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id=?",
			$device_id
		)->to_array('device_type_name', 'device_type_name');
	}

	public static function getAllPreconditionTables()
	{
		$tableNames = array();
		$folder = APP_DIR.'/controllers/deviceforms/classes/components/';
		$tableNames = glob($folder.'*_type.class.php');
		$parameters = array_fill(0, count($tableNames), '.class.php');
		$tableNames = array_map('basename', $tableNames, $parameters);
		return $tableNames;
	}

	/**
	 * Returns devicename, manufacturername and devicetype from device matching device_id
	 *
	 * @param string $device_id
	 * @return array device_name, manufacturername, devicetype
	 */
	public static function getDeviceInformation($device_id)
	{
		$db = DBManager::Get('devices');
		$result = $db->query("SELECT d.device_names_name AS device_name, m.manufacturer_name, ddt.device_type_name FROM device_names as d LEFT JOIN manufacturer as m ON d.manufacturer_id = m.manufacturer_id LEFT JOIN device_device_types as ddt ON d.device_id = ddt.device_id WHERE d.device_id = ? ORDER BY device_names_name ASC", $device_id);
		if(!$result->is_empty())
		{
			while($data = $result->fetchArray())
			{
				$device_data['device_name'][] = $data['device_name'];
				$device_data['manufacturer_name'][] = $data['manufacturer_name'];
				$device_data['main_type'] = $data['device_type_name'];
			}
			$result->release();
			return $device_data;
		}
		return false;
	}

	public static function getAllPreconditionData($tablenames)
	{
		$db = DBManager::Get('devices');
		$query = "SELECT * FROM ";
		$query .= array_pop($tablenames);
		$query .= " LEFT JOIN (";
		$query .= implode(' CROSS JOIN ', $tablenames);
		$query .=");";
	}

	public static function getFieldsFromTables($tables)
	{
		$fields = array();
		foreach($tables as $table_name => $table)
		{
			if(is_array($table))
			{
				foreach($table as $sub_table => $table)
				{
					$object = new $table();
					$field[$table][$sub_table] = $object->toArray();
				}
			}
			else
			{
				$object = new $table();
				$field[$table] = $object->toArray();
			}
		}
		return $field;
	}

	public static function getDeviceTypes()
	{
		return DBManager::Get('devices')->query('SELECT device_type_name FROM device_type')->to_array();
	}

	public static function countDevicesByDevice_Type($device_types)
	{
		$result = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT device_id) AS count, device_type_name FROM device_device_types WHERE device_type_name IN (?) GROUP BY device_type_name",
			array_map(create_function('$a', 'return $a["device_type_name"];'), $device_types)
		)->to_array('device_type_name', 'count');

		$count = array();
		foreach ($device_types as $device_type)
		{
			if (!isset($result[$device_type['device_type_name']]))
			{
				continue;
			}
			$count[$device_type['device_type_name']] = $result[$device_type['device_type_name']];
		}
		return $count;
	}

	public static function getDeviceTypesByManufacturer($manufacturer_id)
	{
		return DBManager::Get('devices')->query("SELECT DISTINCT dt.device_type_name FROM device_type AS dt LEFT JOIN device_device_types AS ddt ON dt.device_type_name = ddt.device_type_name LEFT JOIN device AS d ON ddt.device_id = d.device_id WHERE d.manufacturer_id = ?", $manufacturer_id)->to_array();
	}

	public static function countDevicesByManufacturer($manufacturer_ids)
	{
		return DBManager::Get('devices')->query("SELECT COUNT(DISTINCT device_id) AS count, manufacturer_id FROM device_names WHERE manufacturer_id IN (?) GROUP BY manufacturer_id",
			$manufacturer_ids
		)->to_array('manufacturer_id', 'count');
	}

	public static function countByDeviceTypesAndManufacturer($device_types, $manufacturer_id)
	{
		$count = array();
		foreach($device_types as $device_type)
		{
			$result = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT d.device_id) AS count FROM device AS d LEFT JOIN device_device_types as ddt ON d.device_id = ddt.device_id WHERE ddt.device_type_name = ? AND manufacturer_id = ?", $device_type['device_type_name'], $manufacturer_id);
			while($data = $result->fetch_array())
			{
				$count[$device_type['device_type_name']] = $data['count'];
			}
			$result->release();
		}
		return $count;
	}

	/**
	 * Optimized by tlx
	 */
	public static function getManufacturers($full_info = false)
	{
		return DBManager::Get('devices')->query(
			"SELECT manufacturer_name, manufacturer_id ".
			($full_info ? ", country_id, manufacturer_website " : "").
			"FROM manufacturer ORDER BY manufacturer_name ASC"
		)->to_array('manufacturer_id');
	}

	public static function getManufacturersByDeviceType()
	{
		$manufacturers = false;
		if(isset($_GET['device_type']))
		{
			$manufacturers = DBManager::Get('devices')->query('SELECT DISTINCT m.manufacturer_name, m.manufacturer_id FROM manufacturer AS m LEFT JOIN device AS d ON m.manufacturer_id = d.manufacturer_id LEFT JOIN device_device_types AS dd ON d.device_id = dd.device_id WHERE dd.device_type_name = ?;', strtoupper($_GET['device_type']))->to_array();
		}
		return $manufacturers;
	}

	public static function countByManufacturerAndDeviceType($manufacturers, $device_type)
	{
		$db = DBManager::Get('devices');
		$count = array();
		foreach($manufacturers as $manufacturer)
		{
			$result = $db->query("SELECT COUNT(DISTINCT d.device_id) AS count FROM device AS d LEFT JOIN device_device_types AS ddt ON d.device_id = ddt.device_id WHERE d.manufacturer_id = ? AND ddt.device_type_name = ?", $manufacturer['manufacturer_id'], $device_type);
			while($data = $result->fetch_array())
			{
				$count[$manufacturer['manufacturer_name']] = $data['count'];
			}
			$result->release();
		}
		return $count;
	}

	public static function getDevices()
	{
		return DBManager::Get('devices')->query('SELECT device_id, device_name FROM device ORDER BY device_names ASC')->to_array();
	}

	public static function getDevicesByManufacturer($manufacturer_id=null)
	{
		if ($manufacturer_id === null and !empty($_GET['manufacturer_id']))
		{
			$manufacturer_id = $_GET['manufacturer_id'];
		}

		if (empty($manufacturer_id))
		{
			return false;
		}

		return DBManager::Get('devices')->query("SELECT DISTINCT device_id, device_names_name FROM device_names WHERE manufacturer_id = ? ORDER BY device_names_name ASC",
			$manufacturer_id
		)->to_array('device_id');
	}

	public static function get_device_by_manufacturer($manfacturer_id)
	{
		return DBManager::Get('devices')->query("SELECT DISTINCT device_id, device_names_name FROM device_names WHERE manufacturer_id =? ORDER BY device_names_name ASC", $manfacturer_id)->to_array('device_id', 'device_names_name');
	}

	public static function getDevicesByDeviceType()
	{
		$devices = false;
		if(isset($_GET['device_type']))
		{
			$devices = DBManager::Get('devices')->query('SELECT DISTINCT d.device_id, d.device_name FROM device AS d LEFT JOIN device_device_type AS ddt ON d.device_id = ddt.device_id WHERE ddt.device_type_name = ? ORDER BY device_names_name ASC', $_GET['device_type'])->to_array();
		}
		return $devices;
	}

	public static function getDevicesByDeviceTypeAndManufacturer()
	{
		return DBManager::Get('devices')->query('SELECT DISTINCT d.device_id, d.device_name FROM device AS d LEFT JOIN device_device_types AS ddt ON d.device_id = ddt.device_id WHERE ddt.device_type_name = ? AND d.manufacturer_id = ? ORDER BY device_names_name ASC', $_GET['device_type'], $_GET['manufacturer_id'])->to_array();
	}

	/*
	 * Optimized by tlx
	 */
	public static function getSimilarDevices($device_id, $skip=0, $limit=3)
	{
		return DBManager::Get('devices')->skip($skip)->limit($limit)->query('SELECT ds.compared_id AS compared_id, ds.similarity AS similarity FROM device_similarity AS ds LEFT JOIN device AS d USING (device_id) WHERE ds.device_id = ? ORDER BY ds.similarity DESC',
			$device_id
		)->to_array('compared_id', 'similarity');
	}

	/*
	 * Optimized by tlx
	 */
	public static function getBestDevices($skip=0, $limit=4)
	{
		return DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id, AVG(rating) as rating FROM device_rating GROUP BY device_id HAVING COUNT(rating) > 2 ORDER BY AVG(rating) DESC")->to_array('device_id', 'rating');
	}

	public static function getAllDeviceIds()
	{
		return DBManager::Get('devices')->query("SELECT device_id FROM device")->to_array('device_id', 'device_id');
	}

	public static function get_device_rating($device_id)
	{
		return DBManager::Get('devices')->query("SELECT rating FROM device_rating_unique WHERE device_id = ?", $device_id)->fetch_item();
	}

	public static function get_feature_rating($device_id, $table)
	{
		return DBManager::Get('devices')->query("SELECT AVG(rating) FROM feature_rating WHERE device_id = ? AND rating_name = ?", $device_id, $table)->fetch_item();
	}

	public static function get_table_rating($device_id, $table)
	{
		return DBManager::Get('devices')->query("SELECT AVG(rating) FROM feature_rating WHERE device_id = ? AND rated_table = ?", $device_id, $table)->fetch_item();
	}

	public static function get_tab_ratings($device_id, $tab_name)
	{
		return DBManager::Get('devices')->query("SELECT AVG(tab_rating) FROM tab_rating WHERE device_id = ? AND tab_name = ?", $device_id, $tab_name)->fetch_item();
	}

	public static function get_rating_count($device_id)
	{
		return DBManager::Get('devices')->query("SELECT COUNT(DISTINCT(youser_id)) AS count_user, COUNT(DISTINCT(rated_table)) AS count_table FROM feature_rating WHERE device_id = ?", $device_id)->to_array('count_user', 'count_table');
	}

	public static function get_unconfirmed_devices($device_type = null, $skip = 0, $limit = 10)
	{
		return $device_type == null
			? DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id, youser_id FROM device WHERE confirmed != 'yes'")->to_array()
			: DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT d.device_id, d.youser_id FROM device AS d LEFT JOIN device_device_type as ddt ON d.device_id = ddt.device_id WHERE confirmed != 'yes' AND device_type_name = ? AND main_type = 'yes'", strtoupper($device_type))->to_array('device_id', 'youser_id');
	}

	public static function get_unconfirmed_components($device_id)
	{
		return DBManager::Get('devices')->query("SELECT component_id, table_name FROM device_components WHERE device_id = ?", $device_id)->to_array('component_id', 'table_name');
	}

	public static function get_unconfirmed_devices_by_components($skip = 0, $limit =10)
	{
		return DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT DISTINCT(device_id) as device_id FROM device_components WHERE confirmed = 'no'")->to_array('device_id', 'device_id');
	}

	public static function get_preconditions_from_table($table)
	{
		$table_field = $table.'_name';
		$table_id = $table.'_id';
		return DBManager::Get('devices')->query("SELECT $table_id, $table_field FROM $table;")->to_array($table_id, $table_field);
	}

	public static function get_preset_details($table, $detail)
	{
		$table_field = $table.'_name';
		$table_id = $table.'_id';
		return DBManager::Get('devices')->query("SELECT * FROM $table WHERE $table_field = ? OR $table_field = ?", $detail, strtolower($detail))->to_array();
	}

	public static function get_countries()
	{
		return DBManager::Get('devices')->query("SELECT country_id, country_name FROM country;")->to_array('country_id', 'country_name');
	}

	public static function get_continents()
	{
		return DBManager::Get('devices')->query("SELECT continent_id, continent_name FROM continent;")->to_array('continent_id', 'continent_name');
	}

	public static function device_exists($device_id)
	{
		$db_devices = DBManager::Get('devices')->query("SELECT device_id FROM device WHERE device_id IN (?)", (array)$device_id)->to_array(null, 'device_id');

		return is_array($device_id)
			? array_intersect($device_id, $db_devices)
			: !empty($db_devices);
	}

	public static function get_build_in($device_id)
	{
		return DBManager::Get('devices')->query("SELECT component_name, build_in_status FROM build_in WHERE device_id = ?", $device_id)->to_array('component_name', 'build_in_status');
	}
}
?>