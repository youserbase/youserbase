<?php
class Hook_User_Search extends Hook
{
	public static $hooks = array(
#		'Global:Search'=>'Search',
	);

	public static function Search($needle, &$scope=null)
	{
		return array();

		$original_needle = $needle;
		$needle = '%'.$needle.'%';
		$result = DBManager::Get()->query("SELECT youser_id, nickname, first_name, last_name FROM yousers LEFT JOIN youser_profile USING(youser_id) WHERE visible='yes' AND nickname LIKE ? OR CONCAT(first_name, ' ', last_name) LIKE ? OR last_name LIKE ?", $needle, $needle, $needle);

		$search_results = array();
		while ($row = $result->fetch_array())
		{
			$search_result = new SearchResult_User($row);
			$search_result->set_needle($original_needle);
			array_push($search_results, $search_result);
		}
		$result->release();

		return $search_results;
	}
}
?>