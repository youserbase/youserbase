<?php
class ratingBuilder extends ratingConfig 
{
	
	private static $rating_count;
	
	/**
	 * Gets the Overall-Rating for the device with the specified device_id
	 *
	 * @param $device_id
	 * @return float The rating
	 */
	public static function buildDeviceRating($device_id)
	{
		$avg = investigator::get_device_rating($device_id);
		$rating = self::buildStars($avg);
		return $rating;
	}
	
	private static function buildStars($avg)
	{
		return $input_percent = $avg/5*100;
	}
	
	public static function startBuildFeatureRating($device_type, $device_id, $sheet, $data)
	{
		$ratingform = false;
		$ratingform = self::buildComprarisonFullStarRatingForm(self::$rating, $device_id, $data);
		return array($ratingform, self::$rating_count);
	}
	
	public static function startBuildComparisonFeatureRating($device_type, $device_id, $sheet)
	{
		$ratingform = false;
		$ratingform = self::buildComprarisonFullStarRatingForm(self::$rating, $device_id, $data);
		return array($ratingform, self::$rating_count);
	}
	
	public static function startBuildDeviceRating($device_id)
	{
		$device_rating = self::buildDeviceRating($device_id);
		$count = self::getNumberOfYouserRatings($device_id);
		return array($device_rating, $count);
	}
	
	public static function startBuildTabRating($device_id, $sheet, $data)
	{
		if(!is_array($sheet))
			return false;
		foreach ($sheet as $tab => $tab_tables)
		{
			$inputavg = self::getAverageTabRating($device_id, $tab_tables, $data);
			$rating[$tab] = self::buildStars($inputavg);
		}
		return $rating;
	}
	
	private static function buildComprarisonFullStarRatingForm($rating_fields, $device_id, $data)
	{
		$rating_input = array();
		foreach($rating_fields as $rated_object => $rating_field)
		{
			if(isset($data[$rated_object]['component_id']))
			{
				$component_id = $data[$rated_object]['component_id'];
			}
			else $component_id = 0;
			$rating_input[$rated_object]['avg'] = self::buildQuarterStarRating($component_id, $device_id, $rated_object);
			foreach($rating_field as $field)
			{
				$avg = 0;
				if($component_id !== false && !empty($component_id))
				{
					$avg_count = self::getAverageFeatureRating($field, $component_id);
					$avg = $avg_count[0];
					self::$rating_count[$rated_object] = $avg_count[1];
				}

				 $rating_input[$rated_object][$field] = self::buildStars($avg);
			}
		}
		return $rating_input;
	}
	
	private static function buildQuarterStarRating($component_id, $device_id, $table)
	{
		$avg = 0;
		if($component_id !== false && !empty($component_id))
		{
			$avg = self::getAverageTableRating($component_id, $device_id);
		}
		$rating = self::buildStars($avg);
		return $rating;
	}
	
	private static function getAverageFeatureRating($rating_field, $component_id)
	{
		$db = DBManager::Get('devices');
		$result = $db->query("SELECT AVG(rating) AS avg, COUNT(youser_id) AS count FROM feature_rating WHERE component_id = ? AND feature_rating_name = ?;", $component_id, $rating_field);
		if($result->is_empty())
		{
			return false;
		}
		while ($data = $result->fetchArray())
		{
			$avg = $data['avg'];
			$count = $data['count'];
		}
		$result->release();
		return array($avg, $count);
	}
	
	private static function getAverageTableRating($component_id, $device_id)
	{
		$db = DBManager::Get('devices');
		$avg = 0;
		$result = $db->query("SELECT AVG(rating) AS avg FROM table_rating WHERE component_id = ? AND device_id = ?;", $component_id, $device_id);
		if($result->is_empty())
		{
			return false;
		}
		while ($data = $result->fetchArray())
		{
			$avg = $data['avg'];
		}
		$result->release();
		return $avg;
	}
	
	public static function getAverageDeviceRating($device_id)
	{
		$db = DBManager::Get('devices');
		$result = $db->query("SELECT AVG(tab_rating) AS avg FROM tab_rating WHERE device_id = ?;", $device_id);
		if($result->is_empty())
		{
			return 0;
		}
		while ($data = $result->fetchArray())
		{
			$avg = $data['avg'];
		}
		$result->release();
		if (empty($avg))
		{
			$avg = 0;
		}
		return $avg;
	}
	
	
	public static function getAverageTabRating($device_id, $tab_tables)
	{
		$db = DBManager::Get('devices');
		foreach($tab_tables as $table => $garbage)
		{
			$rating[] = investigator::get_table_rating($device_id, $table);
		}
		$avg = array_sum($rating)/count($rating);
		return $avg;
	}
	
	private static function getNumberOfYouserRatings($device_id)
	{
		$db = DBManager::Get('devices');
		$result = $db->query("SELECT COUNT(DISTINCT youser_id) AS count FROM feature_rating WHERE device_id = ?;", $device_id);
		if($result->is_empty())
		{
			return false;
		}
		while ($data = $result->fetchArray())
		{
			$count = $data['count'];
		}
		$result->release();
		return $count;
	}
}
?>