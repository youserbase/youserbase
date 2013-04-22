<?php
abstract class SearchResult
{
	abstract function get_type();
	abstract function render();
	abstract function identify();

	protected $data = array();
	protected $needle = null;

	public function __construct()
	{
		$arguments = func_get_args();
		if (count($arguments)==1 and is_array($arguments[0]))
		{
			$this->data = $arguments[0];
			return;
		}
		if (count($arguments)==2)
		{
			$this->data[$arguments[0]] = $arguments[1];
		}
	}

	public function set_needle($needle)
	{
		$this->needle = str_replace('%', '', $needle);
	}

	public function score()
	{
		$score = 0;
		foreach ($this->data as $key=>$value)
		{
			$position = stripos($value, $this->needle);
			if ($position===false)
			{
				continue;
			}

			$value_length = strlen($value);
			$score += strlen($this->needle)/$value_length*($value_length-$position)/$value_length*100;
		}

		return min(100, max(0, $score));
	}

	public function display()
	{
		$content = $this->render();
		if ($this->needle!==null)
		{
			$content = preg_replace('/('.preg_quote($this->needle).')(?=[^>]*<)/i', '<span class="highlight">$1</span>', $content);
		}
		return $content;
	}

	public function to_array()
	{
		return $this->data;
	}

	public static function Store($needle, $results)
	{
		foreach ($results as $result)
		{
			if (!is_object($result))
			{
				continue;
			}
			DBManager::Get()->query("INSERT INTO search_results (needle, type, score, identifier, result, timestamp) VALUES (?, ?, ?, ?, ?, NOW())",
				$needle,
				$result->get_type(),
				$result->score(),
				$result->identify(),
				serialize($result)
			);
		}
	}

	public static function GetQuantity($needle, $type=null)
	{
		return $type===null
			? DBManager::Get()->query("SELECT type, COUNT(*) AS quantity FROM search_results WHERE needle=? GROUP BY type WITH ROLLUP", $needle)->to_array('type', 'quantity')
			: DBManager::Get()->query("SELECT COUNT(*) FROM search_results WHERE needle=? AND type=?", $needle, $type)->fetch_item();
	}

	public static function Retrieve($needle, $type=null, $skip=null, $limit=null)
	{
		$query = $type===null
			? "SELECT result FROM search_results WHERE needle=? ORDER BY score DESC, identifier DESC"
			: "SELECT result FROM search_results WHERE needle=? AND type=? ORDER BY score DESC, identifier DESC";

		$db_results = DBManager::Get()->skip($skip)->limit($limit)->query($query, $needle, $type)->to_array(null, 'result');
		if (!$db_results)
		{
			return false;
		}
		$results = array_map('unserialize', $db_results);

		DBManager::Get()->query("UPDATE search_results SET timestamp=NOW() WHERE needle=?", $needle);
		return $results;
	}

	public static function RetrieveArray($needle, $type=null, $skip=null, $limit=null)
	{
		if (($results = self::Retrieve($needle, $type, $skip, $limit))===false)
		{
			return array();
		}

		$result = array();
		foreach ($results as $item)
		{
			array_push($result, array_merge($item->to_array(), array(
				'type'=>$item->get_type()
			)));
		}

		return $result;
	}
}
?>