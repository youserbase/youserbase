<?php
class DBManager
{
	public static $query_count = 0;
	protected static $connections = array();
	const DEFAULT_SCOPE = 'DEFAULT';
	protected static $selected_scope = self::DEFAULT_SCOPE;
	protected static $scopes = array();
	protected static $encodings = array();

	public static function Query($query)
	{
		$arguments = func_get_args();
		return call_user_func_array(array(self::Get(), 'query'), $arguments);
	}

	public static function Plain($query)
	{
		$arguments = func_get_args();
		return call_user_func_array(array(self::Get(), 'explicit_query'), $arguments);
	}

	public static function Get($scope=null)
	{
		if (empty(self::$connections))
		{
			self::Set();
		}

		if ($scope===null)
		{
			$scope = self::$selected_scope;
		}
		$scope = strtoupper($scope);

		if (!isset(self::$connections[$scope]))
		{
			throw new Exception('No connection found for scope "'.$scope.'", maybe you forgot to set the connection via DBManager::Set(<scope>, <connection_string>, <selected>).');
		}
		elseif (is_string(self::$connections[$scope]))
		{
			self::Activate($scope);
		}

		return self::$connections[$scope];
	}

	public static function Set($scope=null, $connection_string=null, $selected=false)
	{
		if ($scope===null)
		{
			$scope = self::DEFAULT_SCOPE;
		}
		$scope = strtoupper($scope);

		if ($connection_string===null)
		{
			if (!defined('DB_CONNECTION_STRING'))
			{
				throw new DBException('No connection string given');
			}
			$connection_string = DB_CONNECTION_STRING;
		}

		self::$connections[$scope] = $connection_string;

		if ($selected)
		{
			self::SelectScope($scope);
		}
	}

	public static function Activate($scope)
	{
		$scope = strtoupper($scope);
		if (is_string(self::$connections[$scope]))
		{
			$connection_string = self::$connections[$scope];

			$parts = parse_url($connection_string);
			if ($parts['scheme']!='mysql')
			{
				throw new DBException('Other db schemes than MySQL are not supported yet');
			}
			self::$scopes[$scope] = substr($parts['path'], 1);

			$classname = sprintf('DBLayer_%s', $parts['scheme']);
			self::$connections[$scope] = new $classname($parts['host'], $parts['user'], $parts['pass']);

			if (!empty($parts['query']))
			{
				parse_str($parts['query'], $options);
				if (!empty($options['prefix']))
				{
					self::$connections[$scope]->set_prefix($options['prefix']);
				}
				if (!empty($options['encoding']))
				{
					self::$encodings[$scope] = $options['encoding'];
				}
			}
		}
		self::$connections[$scope]->select_database(self::$scopes[$scope]);
		if (!empty(self::$encodings[$scope]))
		{
			self::$connections[$scope]->set_encoding(self::$encodings[$scope]);
		}
	}

	public static function SelectScope($scope=null)
	{
		if ($scope===null)
		{
			$scope = self::DEFAULT_SCOPE;
		}
		self::$selected_scope = $scope;
	}

	public static function GetScopes()
	{
		return array_keys(self::$connections);
	}

	public static function GetQueryCount()
	{
		$result = array();
		foreach (self::$connections as $connection)
		{
			if (is_object($connection))
			{
				foreach ($connection->query_count as $action=>$count)
				{
					if (!isset($result[$action]))
					{
						$result[$action] = 0;
					}
					$result[$action] += $count;
				}
			}
		}
		return $result;
	}

	public static function GetQueries()
	{
		$result = array();
		foreach (self::$connections as $scope=>$connection)
		{
			if (is_object($connection))
			{
				$result[$scope] = $connection->queries;
			}
		}
		return $result;
	}

	public static function Reset()
	{
		foreach (array_keys(self::$connections) as $scope)
		{
			self::Activate($scope);
		}
	}
}
?>