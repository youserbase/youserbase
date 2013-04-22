<?php
class surfhistory
{
	public static function addDeviceToHistory($device_id)
	{
		if(Session::Get('history'))
		{
			$history = Session::Get('history');
			array_diff($history, array($device_id));
			array_unshift($history, $device_id);
			$history = array_slice($history, 0, 3);
		}
		else
		{
			$history = array($device_id => time());
		}
		Session::Set('history', $history);
		return $history;
	}

	public static function addDevice($device_id, $history=null)
	{
		if(!empty($history))
		{
			array_unshift($history, $device_id);
		}
		else
		{
			$history = array($device_id);
		}
		return $history;
	}

	public static function getHistory()
	{
		return Session::Get('history')!==null
			? Session::Get('history')
			: false;
	}


	public static function removeDevice($history, $device_id)
	{
		if(count($history) == 0)
			return null;
		$new_history = array_diff($history, $device_id);
		return $new_history;
	}

	public static function getHistoryData($device_ids)
	{
		if (!is_array($device_ids) or empty($device_ids))
		{
			return array();
		}
		foreach($device_ids as $device_id)
		{
			if(is_array($device_id))
			{
				foreach($device_id as $id)
				{
					$return_data[$id] = self::get_data($id);
				}
			}
			else
			{
				$return_data[$device_id] = self::get_data($id);
			}
		}
		return $return_data;
	}
	
	private static function get_data($device_id)
	{
		$device_data = investigator::getDeviceInformation($device_id);
		$device_rating = ratingBuilder::startBuildDeviceRating($device_id);
		$device_picture = getPictures::getPicturePaths($device_id);
		$return_data['device_name'] = $device_data['device_name'];
		$return_data['manufacturer_name'] = $device_data['manufacturer_name'];
		$return_data['main_type'] = $device_data['main_type'];
		$return_data['device_rating'] = $device_rating[0];
		$return_data['device_picture'] = 'assets/images/placeholder.small.png';;
		if($device_picture !== false)
		{
			$return_data['device_picture'] = array_pop($device_picture);
		}
		return $return_data;
	}
}
?>