<?php
class YouserPermissions
{
	private static $cache = array();

	private $youser_id;
	private $roles = array();
	private $permissions = array();

	private function __construct($youser_id)
	{
		$this->youser_id = $youser_id;
		$this->load_permissions();
	}

	public function load_permissions()
	{
		$this->roles = $this->permissions = array();

		$permissions = DBManager::Get()->query("SELECT permission, role, status FROM youser_permission WHERE (youser_id IS NOT NULL AND youser_id=?) OR (youser_id IS NULL AND role IN (SELECT role FROM youser_permission WHERE youser_id=? AND permission IS NULL)) ORDER BY youser_id IS NULL DESC", $this->youser_id, $this->youser_id)->to_array();

		foreach ($permissions as $permission)
		{
			array_push($this->roles, $permission['role']);
			if (!empty($permission['permission']))
			{
				$this->permissions[$permission['permission']] = $permission['status']=='granted';
			}
		}
		$this->roles = array_unique($this->roles);

		return $this;
	}

	public function get_permissions()
	{
		return $this->permissions;
	}

	public function is($role)
	{
		return in_array($role, $this->roles);
	}

	public function granted($permission)
	{
		return isset($this->permissions[$permission]) and $this->permissions[$permission];
	}

	public function get_roles()
	{
		return $this->roles;
	}

	public function clear_roles()
	{
		DBManager::Get()->query("DELETE FROM youser_permission WHERE youser_id=? AND permission IS NOT NULL",
			$this->youser_id
		);
		return $this;
	}

	public function add_role($role)
	{
		$roles = is_array($role)
			? $role
			: explode(',', $role);

		foreach ($roles as $role)
		{
			DBManager::Get()->query("INSERT INTO youser_permission (youser_id, role, status) VALUES (?, ?, 'granted') ON DUPLICATE KEY UPDATE status=VALUES(status)",
				$this->youser_id,
				$role
			);
		}

		return $this;
	}

	public static function Get($youser_id)
	{
		if (empty(self::$cache[$youser_id]))
		{
			self::$cache[$youser_id] = new self($youser_id);
		}
		return self::$cache[$youser_id];
	}

	public static function GetRoles()
	{
		if (empty(self::$cache['roles']))
		{
			self::$cache['roles'] = DBManager::Get()->query("SELECT DISTINCT role FROM youser_permission WHERE role IS NOT NULL ORDER BY role ASC")->to_array(null, 'role');
		}
		return self::$cache['roles'];
	}
}
?>