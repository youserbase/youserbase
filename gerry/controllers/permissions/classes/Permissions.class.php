<?php
class Permissions
{
	public static function CheckPermission($role, $location)
	{
		return DBManager::Query("SELECT FIND_IN_SET(?, permissions) FROM permissions WHERE CONCAT(?, '/') LIKE CONCAT(location, '/%') ORDER BY location DESC LIMIT 1", $role, $location)->fetch_item();
	}

	public static function GetPermissions($role=null, $hierarchic_array=false)
	{
		$query = $role === null
			? "SELECT location, permissions FROM permissions ORDER BY location ASC"
			: "SELECT location, permissions FROM permissions WHERE FIND_IN_SET(?, permissions)";

		$permissions = DBManager::Get()->query($query)->to_array();

		if (!$hierarchic_array)
		{
			return $permissions;
		}

		$result = array();
		foreach ($permissions as $permission)
		{
			list($module,$controller,$method) = explode('/', $permission['location'].'//');
			$current_permissions = explode(',', $permission['permissions']);

			if (!isset($result[$module]))
			{
				$result[$module] = array('controllers'=>array());
			}
			if (empty($controller))
			{
				$result[$module]['permissions'] = $current_permissions;
				continue;
			}
			if (!isset($result[$module]['controllers'][$controller]))
			{
				$result[$module]['controllers'][$controller] = array('methods'=>array());
			}
			if (empty($method))
			{
				$result[$module]['controllers'][$controller]['permissions'] = $current_permissions;
				continue;
			}
			$result[$module]['controllers'][$controller]['methods'][$method] = $current_permissions;
		}
		return $result;
	}

	public static function SetPermission($location, $roles)
	{
		DBManager::Get()->query("INSERT INTO permissions (location, permissions) VALUES (?, ?) ON DUPLICATE KEY UPDATE permissions=VALUES(permissions)",
			$location,
			is_array($roles) ? implode(',', $roles) : $roles
		);
	}

	public static function DeletePermission($location)
	{
		DBManager::Get()->query("DELETE FROM permissions WHERE location=?", $location);
	}

	public static function GetRoles()
	{
		$roles = array();

		$result = DBManager::Get()->query("DESC permissions");
		while ($row = $result->fetch_array())
		{
			if ($row['Field']!='permissions')
			{
				continue;
			}
			eval('$roles = '.str_replace(array('enum(', 'set('), 'array(', $row['Type']).';');
		}
		$result->release();

		return $roles;
	}
}
?>