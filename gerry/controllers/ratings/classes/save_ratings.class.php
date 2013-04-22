<?php
class save_ratings
{
	public static function save_feature_rating($device_id, $table, $feature, $rating)
	{
		$object = call_user_func_array(array('feature_rating', 'Get'), array());
		$object->device_id = $device_id;
		$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
		if($device_id_int === null)
		{
			$device_id_int = 0;
		}
		$object->device_id_int = $device_id_int;
		$object->rated_table = $table;
		$object->rating_name = $feature;
		$object->rating = $rating;
		$object->youser_id = self::get_youser_id();
		$object->language = BabelFish::GetLanguage();
		$object->save();
	}
	
	public static function update_table_rating($device_id, $table)
	{
		$table_rating = 0;
		$result = DBManager::Get('devices')->query("SELECT AVG(rating) FROM feature_rating WHERE rated_table = ? AND device_id = ?;", $table, $device_id)->fetch_item();
		if($result !== null)
		{
			$table_rating = $result;
		}
		self::save_table_rating($device_id, $table, $table_rating);
	}
	
	public static function save_table_rating($device_id, $table, $rating)
	{
		if($rating >= 0)
		{
			$object = call_user_func_array(array('table_rating', 'Get'), array());
			$object->device_id = $device_id;
			$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
			if($device_id_int === null)
			{
				$device_id_int = 0;
			}
			$object->device_id_int = $device_id_int;
			$object->rated_table = $table;
			$object->rating = $rating;
			$object->youser_id = self::get_youser_id();
			$object->language = BabelFish::GetLanguage();
			$object->save();
		}
	}
	
	public static function update_feature_rating($device_id, $table, $rating)
	{
		if($rating >= 0)
		{
			$ratinglist = ratingConfig::$rating;
			if(isset($ratinglist[$table]))
			{
				foreach ($ratinglist[$table] as $feature)
				{
					self::save_feature_rating($device_id, $table, $feature, $rating);
				}
			}
		}
	}
	
	public static function save_tab_rating($device_id, $tab, $rating)
	{
		if($tab != 'COMMENTS')
		{
			$object = call_user_func_array(array('tab_rating', 'Get'), array());
			$object->device_id = $device_id;
			$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
			if($device_id_int === null)
			{
				$device_id_int = 0;
			}
			$object->device_id_int = $device_id_int;
			$object->tab_name = $tab;
			$object->rating = $rating;
			$object->youser_id = self::get_youser_id();
			$object->language = BabelFish::GetLanguage();
			$object->save();
		}
	}
		
	public static function update_tab_rating($device_id, $rating = null)
	{
		$tab_tables = sheetConfig::get_sheet($device_id);
		if($rating == null)
		{
			foreach ($tab_tables as $tab_name => $tables)
			{
				$table = array();
				foreach ($tables as $table_name => $content)
				{
					if(!is_numeric($table_name))
					{
						$table[] = $table_name;
					}
					else if(!is_array($content))
					{
						$table[] = $content;
					}
				}
				$table = implode("' , '", $table);
				
				$tab_rating = DBManager::Get('devices')->query("SELECT AVG(rating) FROM table_rating WHERE rated_table IN ('$table') AND device_id = ?;", $device_id)->fetch_item();
				
				if($tab_rating == null)
				{
					$tab_rating = 0;
				}
				self::save_tab_rating($device_id, $tab_name, $tab_rating);
			}
			return true;
		}
		else
		{
			foreach ($tab_tables as $tab_name => $tables)
			{
				self::save_tab_rating($device_id, $tab_name, $rating);
			}
			foreach (ratingConfig::$rating as $table => $content)
			{
				self::save_table_rating($device_id, $table, $rating);
			}
			return true;
		}
	}
	
	public static function save_device_rating($device_id, $rating)
	{
		if($rating >= 0)
		{
			$object = new device_rating();
			$object->device_id = $device_id;
			$device_id_int = DBManager::Get('devices')->query("SELECT device_id_int FROM device WHERE device_id = ?;", $device_id)->fetch_item();
			if($device_id_int === null)
			{
				$device_id_int = 0;
			}
			$object->device_id_int = $device_id_int;
			$object->rating = $rating;
			$object->youser_id = self::get_youser_id();
			$object->language = BabelFish::GetLanguage();
			$object->save();
		}
	}
	
	public static function update_device_rating($device_id)
	{
		$rating = 0;
		$result = dbManager::Get('devices')->query("SELECT AVG(rating) FROM tab_rating WHERE device_id = ?  AND tab_name != 'COMMENTS';", $device_id)->fetch_item();
		if($result !== null)
		{
			$rating = $result;
		}
		self::save_device_rating($device_id, $rating);
	}
	
	private static function get_youser_id()
	{
		$youser_id = md5($_SERVER['REMOTE_ADDR']);
		if(Youser::Id() !== null)
		{
			$youser_id =  Youser::Id();
		}
		return $youser_id;
	}
}
?>