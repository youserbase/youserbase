<?php
class getratings extends ratingConfig
{
	private static $cache = array(
		'ratings' => array(),
		'amounts' => array(),
	);

	public static function rating($device_id, $tab)
	{
		if(is_array($device_id))
		{
			foreach ($device_id as $id)
			{
				$rating[$id] = self::get_rating($id, $tab);
			}
		}
		else
		{
			$rating[$device_id] = self::get_rating($device_id, $tab);
		}
		return $rating;
	}
	

	public static function get_rating($device_id, $tab)
	{
		$sheet = sheetConfig::get_sheet($device_id);
		$ratingsheet = ratingConfig::$rating;
		$device_rating = self::get_device_rating($device_id);
		$tab_rating[$tab] = self::get_tab_rating($device_id, $tab);
		$table_rating = '';
		if(isset($sheet[$tab])){
			foreach($sheet[$tab] as $table => $table_contents){
				if(!is_array($table_contents) && isset($ratingsheet[$table_contents])){
					$table_rating[$table_contents] = self::get_table_rating($device_id, $table_contents);
				}
				else if(isset($ratingsheet[$table])){
					$table_rating[$table] = self::get_table_rating($device_id, $table);
					/*foreach ($ratingsheet[$table] as $features)
					{
						$feature_rating[$table] = self::get_feature_rating($device_id, $table);
					}*/
				}
			}
		}
		//var_dump($sheet[$tab]).die();
		$rating = array('device_rating' => $device_rating, 'tab_rating' => $tab_rating, 'table_rating' => $table_rating/*, 'feature_rating' => $feature_rating*/);

		return $rating;
	}

	public static function get_device_rating($device_id)
	{
		if (!isset(self::$cache['ratings'][$device_id]))
		{
			self::$cache['ratings'][$device_id] = DBManager::Get('devices')->query("SELECT AVG(rating) FROM device_rating WHERE device_id = ?", $device_id)->fetch_item();
		}
		return self::$cache['ratings'][$device_id];
	}

	public static function get_tab_rating($device_id, $tab, $youser_id = null)
	{
		$tab_rating = 0;
		if($youser_id === null)
		{
			$result = DBManager::Get('devices')->query("SELECT AVG(rating) FROM tab_rating WHERE device_id = ? AND tab_name = ?", $device_id, $tab)->fetch_item();
		}
		else
		{
			$result = DBManager::Get('devices')->query("SELECT AVG(rating) FROM tab_rating WHERE device_id = ? AND tab_name = ? AND youser_id = ;", $device_id, $tab, $youser_id)->fetch_item();
		}
		if($result !== null)
		{
			$tab_rating = self::form_numbers($result);
		}
		return $tab_rating;
	}

	public static function get_table_rating($device_id, $table, $youser_id = null)
	{
		$table_rating = 0;
		if($youser_id === null)
		{
			$result = DBManager::Get('devices')->query("SELECT AVG(rating) FROM table_rating WHERE device_id = ? AND rated_table = ?", $device_id, strtolower($table))->fetch_item();
		}
		else
		{
			$result = DBManager::Get('devices')->query("SELECT rating FROM table_rating WHERE device_id = ? AND rated_table = ? AND youser_id = ?", $device_id, strtolower($table), $youser_id)->fetch_item();
		}
		if ($result !== null)
		{
			$table_rating = self::form_numbers($result);
		}
		return $table_rating;
	}

	public static function get_feature_rating($device_id, $table, $youser_id = null)
	{
		$ratingsheet = ratingConfig::$rating;
		foreach ($ratingsheet[$table] as $feature)
		{
			$feature_rating[$feature] = 0;
			if($youser_id == null)
			{
				$result = DBManager::Get('devices')->query("SELECT AVG(rating) FROM feature_rating WHERE device_id = ? AND rated_table = ? AND rating_name = ?", $device_id, strtolower($table), strtolower($feature))->fetch_item();
			}
			else
			{
				$result =  DBManager::Get('devices')->query("SELECT rating FROM feature_rating WHERE device_id = ? AND rated_table = ? AND rating_name = ? AND youser_id = ?", $device_id, strtolower($table), strtolower($feature), $youser_id)->fetch_item();
			}
			if($result != null)
			{
				$feature_rating[$feature] = self::form_numbers($result);
			}
		}
		return $feature_rating;
	}

	public static function get_number_of_ratings($device_id)
	{
		if(is_array($device_id))
		{
			foreach ($device_id as $id)
			{
				$amount[$id] = self::get_amount($id);
			}
		}
		else
		{
			$amount = self::get_amount($device_id);
		}
		return $amount;
	}

	private static function get_amount($device_id)
	{
		if (!isset(self::$cache['amounts'][$device_id]))
		{
			self::$cache['amounts'][$device_id] = 0 + DBManager::Get('devices')->query("SELECT COUNT(youser_id) FROM device_rating WHERE device_id = ?", $device_id)->fetch_item();
		}
		return self::$cache['amounts'][$device_id];
	}

	private static function form_numbers($number)
	{
		return ($number < 10)
			? numberformat($number, 1)
			: numberformat($number, 0);
	}
}

?>