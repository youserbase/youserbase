<?php
/**
 * Connection
 *
 * @author Jan-Hendrik Willms <tleilax@mindfuck.de>
 */
class Connection
{
	/**
	 * Caches all connection details to save db queries
	 * @var array
	 */
	private static $cache = array();

	/**
	 * Get all connections of a specific youser
	 *
	 * @param string $youser_id
	 * @param mixed $skip
	 * @param mixed $limit
	 * @return array of youser objects
	 */
	public static function Get($youser_id, $skip=null, $limit=null)
	{
		$query = "SELECT IF(youser_id_a=?, youser_id_b, youser_id_a) AS youser_id FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND confirmed IS NOT NULL ORDER BY confirmed DESC";

		$result = DBManager::Get()->skip($skip)->limit($limit)->query($query, $youser_id, $youser_id)->to_array(null, 'youser_id');
		return Youser::Get($result);
	}

	/**
	 * Get connection count of a specific youser
	 *
	 * @param string $youser_id
	 * @return int
	 */
	public static function GetCount($youser_id)
	{
		return DBManager::Get()->query("SELECT COUNT(*) FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND confirmed IS NOT NULL", $youser_id)->fetch_item();
	}

	/**
	 * Tests whether two yousers are connected
	 *
	 * @param string $youser_a
	 * @param string $youser_b
	 * @return bool
	 */
	public static function Connected($youser_a, $youser_b)
	{
		if (!isset(self::$cache[$youser_a]) or !isset(self::$cache[$youser_a][$youser_b]))
		{
			if (empty(self::$cache[$youser_a]))
			{
				self::$cache[$youser_a] = array();
			}
			if (empty(self::$cache[$youser_b]))
			{
				self::$cache[$youser_b] = array();
			}

			$result = DBManager::Get()->query("SELECT 1 FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND ? IN (youser_id_a, youser_id_b) AND confirmed IS NOT NULL",
				$youser_a,
				$youser_b
			)->is_empty();

			self::$cache[$youser_a][$youser_b] = !$result;
			self::$cache[$youser_b][$youser_a] = !$result;
		}

		return self::$cache[$youser_a][$youser_b];
	}

	/**
	 * Connects two yousers
	 *
	 * @param string $youser_a
	 * @param string $youser_b
	 * @return bool
	 */
	public static function Connect($youser_a, $youser_b)
	{
		$initiated = $youser_a;
		if ($youser_b<$youser_a)
		{
			$temp = $youser_a;
			$youser_a = $youser_b;
			$youser_b = $temp;
		}

		DBManager::Get()->query("INSERT IGNORE INTO youser_network (youser_id_a, youser_id_b, initiated, confirmed) VALUES (?, ?, ?, NULL)",
			$youser_a,
			$youser_b,
			$initiated
		);
		return DBManager::Get()->affected_rows()>0;
	}

	public static function Confirm($youser_id_a, $youser_id_b)
	{
		if ($youser_id_b<$youser_id_a)
		{
			$temp = $youser_id_a;
			$youser_id_a = $youser_id_b;
			$youser_id_b = $temp;
		}

		DBManager::Get()->query("UPDATE youser_network SET confirmed=NOW() WHERE youser_id_a=? AND youser_id_b=?", $youser_id_a, $youser_id_b);

		return DBManager::Get()->affected_rows()>0;
	}

	/**
	 * Removes a connection between two yousers
	 *
	 * @param string $youser_a
	 * @param string $youser_b
	 * @return bool
	 */
	public static function Remove($youser_a, $youser_b)
	{
		DBManager::Get()->query("DELETE FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND ? IN (youser_id_a, youser_id_b)",
			$youser_a,
			$youser_b
		);
		return DBManager::Get()->affected_rows();
	}

	/**
	 * Finds the connection between two yousers
	 *
	 * @param string $youser_a
	 * @param string $youser_b
	 * @return mixed
	 */
	public static function Find($youser_a, $youser_b)
	{
		if ($youser_a==$youser_b)
		{
			return null;
		}
		if (self::Connected($youser_a, $youser_b))
		{
			return array($youser_a, $youser_b);
		}
		if ($youser_b<$youser_a)
		{
			$temp = $youser_a;
			$youser_a = $youser_b;
			$youser_b = $temp;
		}

		$result = DBManager::Get()->query("SELECT n0.youser_id_a AS y0, n0.youser_id_b AS y1, n1.youser_id_a AS y2, n1.youser_id_b AS y3 FROM youser_network n0 INNER JOIN youser_network n1 ON (n0.youser_id_a=n1.youser_id_a OR n0.youser_id_a=n1.youser_id_b OR n0.youser_id_b=n1.youser_id_a OR n0.youser_id_b=n1.youser_id_b AND n0.confirmed IS NOT NULL AND n1.confirmed IS NOT NULL) WHERE ? = n0.youser_id_a AND ? IN (n1.youser_id_a, n1.youser_id_b) AND NOT n0.youser_id_a=n1.youser_id_a AND NOT n0.youser_id_b=n1.youser_id_b ORDER BY RAND(UNIX_TIMESTAMP()*UNIX_TIMESTAMP()) LIMIT 0,1", $youser_a, $youser_b)->fetch_item(array());
		if ($result!==null)
		{
			$return = array($youser_a, $youser_b);
			foreach ($result as $item)
			{
				if ($item!=$youser_a and $item!=$youser_b)
				{
					array_splice($return, 1, 0, $item);
					break;
				}
			}
			return $return;
		}
		return false;
	}

	public static function FindOpenRequests($youser_id)
	{
		return DBManager::Get()->query("SELECT IF(youser_id_a=?, youser_id_b, youser_id_a) FROM youser_network WHERE ? IN (youser_id_a, youser_id_b) AND NOT initiated=? AND confirmed IS NULL", $youser_id, $youser_id, $youser_id)->to_array(null, ':1');
	}
}
?>