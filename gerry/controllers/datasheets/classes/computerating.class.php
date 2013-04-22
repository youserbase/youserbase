<?php
class computerating
{

	/**
	 * computes the Rating for a table based on the tables features ratings
	 *
	 * @param $device_id specifiyng the rated device
	 * @param $table specifiyng the rated table
	 */
	public static function compute_table_rating($device_id)
	{
		foreach(ratingConfig::$rating as $rated_table => $rating_fields)
		{
			$table_rating[$rated_table][$rated_table.'_avg'] = min(investigator::get_table_rating($device_id, $rated_table)/5*100, 100);
			if($table_rating[$rated_table][$rated_table.'_avg'] === null)
			{
				$table_rating[$rated_table][$rated_table.'_avg'] = 0;
			}
			foreach ($rating_fields as $field)
			{
				$table_rating[$rated_table][$field] = min(investigator::get_feature_rating($device_id, $field) /5 *100, 100);
				if($table_rating[$rated_table][$field] === null)
				{
					$table_rating[$rated_table][$field] = 0;
				}
			}
		}
		return $table_rating;
	}

	/**
	 * computes the Rating for a tabl based on the tabs tables rating
	 *
	 * @param $device_id specifiyng the rated device
	 * @param $tab the name of the rated tab
	 * @param array $tab_tables containing the tables of the tab
	 */
	public static function compute_tab_rating($device_id, $table_ratings)
	{
		foreach(phoneConfig::$sheet as $tab => $tab_tables)
		{
			$rating[$tab] = 0;

			foreach ($tab_tables as $table => $table_fields)
			{
				if(isset($table_ratings[$device_id][$table]))
				{
					$tab_rating[$tab][$table] = $table_ratings[$device_id][$table][$table.'_avg'];
				}
			}
			if(isset($tab_rating[$tab]))
				$rating[$tab] = max( array_sum($tab_rating[$tab])/count($tab_rating[$tab]), 100);
		}
		return $rating;
	}

	/**
	 * computes the Rating for a device baserd on the devices tab_ratings
	 *
	 * @param $device_id
	 */
	public static function compute_device_rating($device_ids, $tab_ratings, $compare = true)
	{
		$faktor = array(1);
		foreach($device_ids as $device_id)
		{
			foreach($tab_ratings[$device_id] as $tab => $tab_rating)
			{
				$faktor[$tab] = 1;
				if($compare)
				{
					if(Session::Get($tab.'_rating_mod') !== false)
					{
						$faktor[$tab] = Session::Get($tab.'_rating_mod');
					}
					if(isset($_POST[$tab.'_rating_mod']) !== false)
					{
						$faktor[$tab] = $_POST[$tab.'_rating_mod'];
						Session::Set($tab.'_rating_mod', $faktor[$tab]);
					}
				}
				$rating[$tab] = $tab_rating * $faktor[$tab];
			}
			$device_rating[$device_id] = min(array_sum($rating) / array_sum($faktor), 100);
		}
		arsort($device_rating);
		if($compare)
		{
			return array($device_rating, $faktor);
		}
		return $device_rating;
	}
}
?>