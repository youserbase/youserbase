<?php
/**
 * Rudimentärer Ansatz, bislang wird nur der Name gespeichert
 *
 */
class Manufacturer_Name
{
	private static $cache = array();

	public static function Lookup($manufacturer)
	{
		$manufacturer_id = false;
		foreach (self::$cache as $id => $data)
		{
			if (BabelFish::Get($data['name'])==$manufacturer)
			{
				$manufacturer_id = $id;
				break;
			}
		}

		if (!$manufacturer_id and ($phrase_id = BabelFish::ReverseLookup($manufacturer, 'MANU_')))
		{
			$manufacturer_data = DBManager::Get('devices')->query("SELECT manufacturer_id, manufacturer_name, COUNT(DISTINCT device_id) AS `devices` FROM manufacturer LEFT JOIN device_names USING(manufacturer_id) WHERE manufacturer_name = ? GROUP BY manufacturer_id", $phrase_id)->to_array('manufacturer_id', array('manufacturer_name', 'devices'));
			foreach ($manufacturer_data as $id => $row)
			{
				self::$cache[$id] = array(
					'id'=>$id,
					'name'=>$row['manufacturer_name'],
					'short_name'=>strtolower(BoxBoy::RemoveSpecialCharacters(str_replace(array('MANU_', '_'), '', $row['manufacturer_name']))),
					'devices'=>$row['devices'],
				);
				$manufacturer_id = $id;
			}
			$rank_data = DBManager::Query("SELECT manufacturer_id, rank FROM statistics_daily_manufacturer_ranks WHERE manufacturer_id IN (?) AND daystamp=(SELECT MAX(daystamp) FROM statistics_daily_manufacturer_ranks)",
				array_keys($manufacturer_data)
			)->to_array('manufacturer_id', 'rank');
			foreach ($rank_data as $id=>$rank)
			{
				if (isset(self::$cache[ $id ]))
				{
					self::$cache[ $id ]['rank'] = $rank;
				}
			}
		}
		return $manufacturer_id;
	}

	public static function Get($manufacturer_id)
	{
		$return_array = is_array($manufacturer_id);

		$manufacturer_ids_not_in_cache = array_diff( (array)$manufacturer_id, array_keys(self::$cache));
		if (!empty($manufacturer_ids_not_in_cache))
		{
			$manufacturer_data = DBManager::Get('devices')->query("SELECT manufacturer_id, manufacturer_name, COUNT(DISTINCT device_id) AS `devices` FROM manufacturer LEFT JOIN device_names USING(manufacturer_id) WHERE manufacturer_id IN (?) GROUP BY manufacturer_id",
				$manufacturer_ids_not_in_cache
			)->to_array('manufacturer_id', array('manufacturer_name', 'devices'));
			foreach ($manufacturer_data as $id => $data)
			{
				self::$cache[$id] = array(
					'id'=>$id,
					'name'=>$data['manufacturer_name'],
					'short_name'=>strtolower(BoxBoy::RemoveSpecialCharacters(str_replace(array('MANU_', '_'), '', $data['manufacturer_name']))),
					'devices'=>$data['devices'],
				);
			}

			$rank_data = DBManager::Query("SELECT manufacturer_id, rank FROM statistics_daily_manufacturer_ranks WHERE manufacturer_id IN (?) AND daystamp=(SELECT MAX(daystamp) FROM statistics_daily_manufacturer_ranks)",
				$manufacturer_ids_not_in_cache
			)->to_array('manufacturer_id', 'rank');
			foreach ($rank_data as $id=>$rank)
			{
				if (isset(self::$cache[ $id ]))
				{
					self::$cache[ $id ]['rank'] = $rank;
				}
			}
		}

		$result = array();
		foreach ((array)$manufacturer_id as $id)
		{
			if (isset(self::$cache[$id]))
			{
				$result[$id] = self::$cache[$id];
			}
		}

		uasort($result, create_function('$a, $b', 'return strcmp($a["short_name"], $b["short_name"]);'));

		return $return_array
			? $result
			: array_pop($result);
	}

	public static function GetAll()
	{
		$id = DBManager::Get('devices')->query("SELECT DISTINCT manufacturer_id FROM device_names")->to_array(null, 'manufacturer_id');
		return self::Get( $id );
	}
}
?>
