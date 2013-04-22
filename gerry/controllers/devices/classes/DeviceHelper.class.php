<?php
class DeviceHelper
{
	public static $image_sizes = array(
		'avatar'=>array(-58, -58),
		'small'=>array(80, 80),
		'consultant'=>array(-100, -100),
		'medium'=>array(120, 120),
		'large'=>array(300, 300),
		'xl'=>array(600, 600),
	);

	private static $device_data_cache = array();
	private static $cache = array(
		'pictures' => array(),
	);

	public static function GetImage($device_id, $type, $image=null)
	{
		if ($image === null)
		{
			$device = Device::Get($device_id);

			$image = null;
			foreach ($device['pictures_full'] as $index => $picture)
			{
				if ($picture['master_image']!='yes')
				{
					continue;
				}
				$image = $index;
			}
			if ($image === null)
			{
				$image = reset($device['pictures']);
			}
		}

		$index = hexdec(substr(md5($device_id.$image.$type), -4)) % count($GLOBALS['ASSETS_URLS']);
		$url = $GLOBALS['ASSETS_URLS'][$index];

		return $url.'Device/'.$device_id.'/'.$image.'.'.$type.'.png';
	}

	public static function GetLink($device_id, $manufacturer=null, $model=null, $parameters=array())
	{
		if (isset($parameters['tab']) and $parameters['tab']=='common')
		{
			unset($parameters['tab']);
		}
		return Frontcontroller::GetLink('Datasheets', 'datasheets', 'page', array_merge($parameters, array('device_id'=>$device_id)));
	}

	public static function GetName($device_id)
	{
		$device = Device::Get($device_id);
		$manufacturer = is_array($device['manufacturer'])
			? reset($device['manufacturer'])
			: $device['manufacturer'];
		$model = is_array($device['name'])
			? reset($device['name'])
			: $device['name'];

		return BabelFish::Get($manufacturer).' '.BabelFish::Get($model);
	}

	public static function GetDeviceCount()
	{
		if (empty(self::$cache['device_count']))
		{
			self::$cache['device_count'] = DBManager::Get('devices')->query("SELECT COUNT(*) FROM device")->fetch_item();
		}
		return self::$cache['device_count'];
	}

	public static function GetDevicePictureCount()
	{
		if (empty(self::$cache['device_picture_count']))
		{
			self::$cache['device_picture_count'] = DBManager::Get('devices')->query("SELECT COUNT(DISTINCT device_id) FROM device_pictures")->fetch_item();
		}
		return self::$cache['device_picture_count'];
	}

	public static function GetPictureCount($device_id)
	{
		$device_ids = (array) $device_id;

		$not_in_cache = array_diff($device_ids, array_keys(self::$cache['pictures']));

		if (!empty($not_in_cache))
		{
			self::$cache['pictures'] = array_merge(self::$cache['pictures'], array_fill_keys($not_in_cache, 0));
			self::$cache['pictures'] = array_merge(self::$cache['pictures'], DBManager::Get('devices')->query("SELECT device_id, 0 + COUNT(*) AS quantity FROM device_pictures WHERE device_id IN (?) GROUP BY device_id", $not_in_cache)->to_array('device_id', 'quantity'));
		}

		$result = array();
		foreach ($device_ids as $id)
		{
			$result[$id] = self::$cache['pictures'][$id];
		}

		return is_array($device_id)
			? $result
			: self::$cache['pictures'][$device_id];
	}

	public static function InsertImage($device_id, $index, $type)
	{
		$path = ASSETS_IMAGE_DIR.'devices/'.substr($device_id, max(-strlen($device_id), -4)).'/';

		if (!file_exists($path) and !mkdir($path))
		{
			Dobber::ReportError('Konnte Pfad zu den Bildern nicht anlegen');
			return;
		}
		@chmod($path, 0777);

		$master_image = !DBManager::Get('devices')->query("SELECT 1 FROM device_pictures WHERE device_id=? AND master_image='yes'", $device_id)->fetch_item();
		$image_id = DBManager::Get('devices')->query("SELECT MAX(device_pictures_id) FROM device_pictures WHERE device_id=?", $device_id)->fetch_item();
		$image_id = $image_id+1;
		DBManager::Get()->query("INSERT INTO device_picture_count (device_id, picture_count) VALUES (?, 1) ON DUPLICATE KEY UPDATE picture_count = picture_count + 1", $device_id);

		foreach (Helper::GetUploadedFiles($index) as $file)
		{
			$filename = $path.$device_id.'_'.$image_id.'.original';

			move_uploaded_file($file['tmp_name'], $filename);
			chmod($filename, 0777);

			Images::Scale($filename, $device_id.'_'.$image_id, $path, self::$image_sizes);

			DBManager::Get('devices')->query("INSERT INTO device_pictures (device_pictures_id, device_id, master_image, device_pictures_type, original_filename, youser_id, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())",
				$image_id,
				$device_id,
				$master_image ? 'yes' : 'no',
				$type,
				$file['name'],
				Youser::Id()
			);

			$master_image = false;
			$image_id = $image_id+1;

			Event::Dispatch('alert', 'Device:ImageAdded', Youser::Id(), $device_id, $image_id);
		}
	}

	public static function RemoveImage($device_id, $picture_id)
	{
		// Physically remove
		$path = ASSETS_IMAGE_DIR.'devices/'.substr($device_id, max(-strlen($device_id), -4)).'/';
		@unlink($path.$picture_id.'.original');
		@unlink($path.$device_id.'_'.$picture_id.'.original');
		array_map('unlink', glob($path.$device_id.'_'.$picture_id.'.*'));

		// Remove from db
		DBManager::Get('devices')->query("DELETE FROM device_pictures WHERE device_id=? AND device_pictures_id=?", $device_id, $picture_id);
	}

	public static function GetPictureTypes()
	{
		static $types = null;

		if ($types === null)
		{
			$types = DBManager::Get('devices')->query("DESC device_pictures")->to_array('Field', 'Type');
			foreach ($types as $index => $type)
			{
				if (strpos($type, 'enum(') === false)
				{
					unset($types[$index]);
					continue;
				}
				eval('$types[$index] = '.str_replace('enum(', 'array(', $type).';');
			}
		}
		return $types;
	}
}
?>