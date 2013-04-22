<?php
class Config
{
	protected static $data = null;
	protected static $changed = false;

	public static function Get($scope, $key=null, $type='system')
	{
		if (self::$data[$type] === null)
		{
			self::LoadConfig();
		}
		if ($key === null)
		{
			list($scope, $key) = explode(':', $scope);
		}
		if (isset(self::$data[$type][$scope]) and isset(self::$data[$type][$scope][$key]))
		{
			return self::$data[$type][$scope][$key];
		}
		return null;
	}

	public static function SetConfiguration($configuration, $type = 'system')
	{
		self::$data[$type] = $configuration;
		self::StoreConfig($type);
	}

	public static function Set($scope, $key, $value, $type = 'system')
	{
		if (!isset(self::$data[$type]))
		{
			self::$data[$type] = array();
		}
		if (!isset(self::$data[$type][$scope]))
		{
			self::$data[$type][$scope] = array();
		}
		self::$data[$type][$scope][$key] = $value;

		self::StoreConfig($type);
	}

	private static function StoreConfig($type)
	{
		$query_values = array();
		$arguments = array();
		foreach (self::$data[$type] as $scope=>$values)
		{
			foreach ($values as $key=>$value)
			{
				array_push($query_values, '(?,?,?,?)');
				array_push($arguments, $type, $scope, $key, $value);
			}
		}

		$query = "INSERT INTO config (type, scope, `key`, value) VALUES ".implode(',', $query_values)." ON DUPLICATE KEY UPDATE value=VALUES(value)";

		array_unshift($arguments, $query);
		call_user_func_array(array(DBManager::Get(), 'query'), $arguments);
	}

	private static function LoadConfig()
	{
		self::$data = array();

		$result = DBManager::Get()->query("SELECT scope, `key`, value, type FROM config");
		while ($row = $result->fetch_array())
		{
			if (!isset(self::$data[$row['type']]))
			{
				self::$data[$row['type']] = array();
			}
			if (!isset(self::$data[$row['type']][$row['scope']]))
			{
				self::$data[$row['type']][$row['scope']] = array();
			}
			self::$data[$row['type']][$row['scope']][$row['key']] = $row['value'];
		}
		$result->release();
	}
}
?>