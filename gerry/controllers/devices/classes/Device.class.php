<?php
class Device
{
	public $device_id;
	public $device_id_int;
	public $device_name;
	public $ean;
	public $confirmed;
	public $timestamp;
	public $youser_id;

	private static $cache = array(
		'devices' => array(),
		'lookup' => array(),
	);

	public static function Invalidate($device_id)
	{
		if (isset(self::$cache['devices'][$device_id]))
			unset(self::$cache['devices'][$device_id]);
	}

	public static function Get($device_id)
	{
		$return_array = is_array($device_id);

		$device_ids_not_in_cache = array_diff((array)$device_id, array_keys(self::$cache['devices']));
		if (!empty($device_ids_not_in_cache))
		{
			$device_data = DBManager::Get('devices')->query("SELECT device_names.device_id, device_names_name, manufacturer_name, device_names.manufacturer_id, AVG(rating) as rating, UNIX_TIMESTAMP(device.timestamp) AS timestamp, 0 + COUNT(DISTINCT device_pictures_id) AS pictures FROM device_names LEFT JOIN device USING(device_id) LEFT JOIN device_rating ON device_names.device_id = device_rating.device_id LEFT JOIN manufacturer ON device_names.manufacturer_id = manufacturer.manufacturer_id LEFT JOIN device_pictures ON device.device_id=device_pictures.device_id WHERE device_names.device_id IN (?) GROUP BY device_id",
				array_unique($device_ids_not_in_cache)
			)->to_array('device_id');
			foreach ($device_data as $id => $data)
				self::$cache['devices'][ $id ] = array(
					'id'           => $data['device_id'],
					'name'         => $data['device_names_name'],
					'manufacturer' => $data['manufacturer_name'],
					'manufacturer_id'	=> $data['manufacturer_id'],
					'rating'       => $data['rating']<10?numberformat($data['rating'],1,'.'):numberformat($data['rating'],0),
					'timestamp'    => $data['timestamp'],
					'picture_count' => $data['pictures'],
					'pictures'     => array(),
					'pictures_full' => array(),
					'rank'         => null,
				);

			if (!empty($device_data))
			{
				$picture_data = DBManager::Get('devices')->query("SELECT device_pictures_id AS id, device_id, device_pictures_type AS type, master_image, angle, situation, youser_id, UNIX_TIMESTAMP(timestamp) AS timestamp, original_filename FROM device_pictures WHERE device_id IN (?) ORDER BY device_pictures_type ASC, position ASC, angle ASC, situation ASC",
					array_keys($device_data)
				)->to_array();
				foreach ($picture_data as $data)
				{
					array_push(self::$cache['devices'][ $data['device_id'] ]['pictures'], $data['id']);
					self::$cache['devices'][ $data['device_id'] ]['pictures_full'][$data['id']] = $data;
				}

				$last_day = DBManager::Query("SELECT MAX(daystamp) FROM statistics_daily_device_ranks")->fetch_item();
				$rank_data = DBManager::Query("SELECT device_id, rank FROM statistics_daily_device_ranks WHERE device_id IN (?) AND daystamp = ?",
					$device_ids_not_in_cache,
					$last_day
				)->to_array('device_id', 'rank');
				foreach ($rank_data as $id=>$rank)
					if (isset(self::$cache['devices'][$id]))
						self::$cache['devices'][ $id ]['rank'] = $rank;
			}
		}

		$devices = array();
		foreach ((array)$device_id as $id)
			if (isset(self::$cache['devices'][$id]))
				$devices[$id] = self::$cache['devices'][$id];

		return $return_array
			? $devices
			: array_shift($devices);
	}

	public static function GetByManufacturerId($manufacturer_id)
	{
		$device_ids = DBManager::Get('devices')->query("SELECT device_id FROM device_names WHERE manufacturer_id=? ORDER BY device_names_name ASC",
			$manufacturer_id
		)->to_array(null, 'device_id');

		return self::Get($device_ids);
	}

	public static function Lookup($manufacturer, $model)
	{
		$hash = md5($manufacturer.'|'.$model);
		if (!isset(self::$cache['lookup'][$hash]))
		{
			$temp_manufacturer = BabelFish::ReverseLookup($manufacturer, 'MANU_', true);
			$temp_model = BabelFish::ReverseLookup($model, 'DEVICE_', true);

			// Fallback if no phrase is found
			if (empty($temp_model))
				$temp_model = $model;
			if (empty($temp_manufacturer))
				$temp_manufacturer = $manufacturer;

			self::$cache['lookup'][$hash] = DBManager::Get('devices')->limit(1)->query("SELECT device_id FROM device_names LEFT JOIN manufacturer USING (manufacturer_id) WHERE manufacturer_name IN (?) AND device_names_name IN (?)",
				(array)$temp_manufacturer,
				(array)$temp_model
			)->fetch_item();

		}
		return self::$cache['lookup'][$hash];
	}

	public function save()
	{
		if($this->youser_id===null)
			$this->youser_id = md5(uniqid("device", true)."/".time());

		$db = DBManager::Get("devices");
		$db->query("INSERT INTO device (device_id, device_id_int, device_name, ean, confirmed, timestamp, youser_id) VALUES (?, ?, ?, ?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE device_id=VALUES(device_id), device_id_int=VALUES(device_id_int), device_name=VALUES(device_name), ean=VALUES(ean), confirmed=VALUES(confirmed), timestamp=VALUES(timestamp), youser_id=VALUES(youser_id)",$this->device_id, $this->device_id_int, $this->device_name, $this->ean, $this->confirmed, $this->youser_id);

		return $db->affected_rows()>0;
	}

	public function getdevice_id_int($device_id)
	{
		return DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
	}
}
?>