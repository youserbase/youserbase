<?php
class Youser
{
	private static $cache = array();
	private static $cache_nickname_loopup = array();

	public $permissions = null;

	public $id = null;
	public $nickname = null;
	public $password = null;
	public $email = null;
	public $role = 'youser';
	public $secret = null;
	public $blocked = null;
	public $visible = true;
	public $language = null;
	public $roles = array();

	private $attributes = null;

	public function __construct($id=null, $nickname=null)
	{
		if ($id !== null)
		{
			$this->id = $id;
			$this->load();
		}
		elseif ($nickname !== null)
		{
			$this->nickname = $nickname;
			$this->load(true);
		}
	}

	private function load($by_nickname=false)
	{
		if (!$by_nickname and isset(self::$cache[$this->id]))
		{
			$this->LoadFromCache($this->id);
			return;
		}
		elseif ($by_nickname and isset(self::$cache_nickname_loopup[$this->nickname]) and isset(self::$cache[self::$cache_nickname_loopup[$this->nickname]]))
		{
			$this->LoadFromCache(self::$cache_nickname_loopup[$this->nickname]);
			return;
		}

		$result = $by_nickname
			? DBManager::Get()->query("SELECT youser_id, nickname, password, email, role, secret, blocked, visible-1 AS visible, language FROM yousers WHERE nickname=?", $this->nickname)
			: DBManager::Get()->query("SELECT youser_id, nickname, password, email, role, secret, blocked, visible-1 AS visible, language FROM yousers WHERE youser_id=?", $this->id);
		if ($result->is_empty())
		{
			throw new Exception(__CLASS__.'/'.__FUNCTION__.': Malformed data');
		}
		$data = $result->fetch_array();
		$result->release();

		$this->id = $data['youser_id'];
		$this->nickname = $data['nickname'];
		$this->password = $data['password'];
		$this->email = $data['email'];
		$this->role = $data['role'];
		$this->secret = $data['secret'];
		$this->blocked = $data['blocked'];
		$this->visible = $data['visible'];
		$this->language = $data['language'];

		self::$cache[$this->id] = $this;
		self::$cache_nickname_loopup[$this->nickname] = $this->id;
	}

	private function LoadFromCache($youser_id)
	{
		$cache_youser = self::$cache[$youser_id];

		$this->id = $youser_id;
		$this->nickname = $cache_youser->nickname;
		$this->password = $cache_youser->password;
		$this->email = $cache_youser->email;
		$this->role = $cache_youser->role;
		$this->secret = $cache_youser->secret;
		$this->blocked = $cache_youser->blocked;
		$this->visible = $cache_youser->visible;
		$this->language = $cache_youser->language;
	}

	public function save()
	{
		if ($this->id===null)
		{
			$this->secret = md5(uniqid('youser_secret', true).'/'.time());
		}
		if (!$this->isValid())
		{
			throw new Exception(__CLASS__.'/'.__FUNCTION__.': Malformed data');
		}
		DBManager::Get()->query("INSERT INTO yousers (youser_id, nickname, email, role, password, secret, blocked, visible, register_timestamp, language) VALUES (?,TRIM(?),TRIM(?),?,?,?,?,?, NOW(), ?) ON DUPLICATE KEY UPDATE nickname=VALUES(nickname), email=VALUES(email), role=VALUES(role), password=VALUES(password), blocked=VALUES(blocked), visible=VALUES(visible), language=VALUES(language)",
			$this->id,
			$this->nickname,
			$this->email,
			$this->role,
			$this->password,
			$this->secret,
			($this->blocked=='yes' or $this->blocked===true) ? 'yes' : 'no',
			($this->visible=='yes' or $this->visible===true) ? 'yes' : 'no',
			$this->language
		);
		if ($this->id===null)
		{
			$this->id = DBManager::Get()->get_inserted_id();
		}
		if (DBManager::Get()->affected_rows()===1)
		{
			Event::Dispatch('alert', 'Youser:Create', $this->id);
		}
		return DBManager::Get()->affected_rows()>=0;
	}

	public function setPassword($password)
	{
		$this->password = md5($password);
	}

	public function set_attribute($attribute, $value, $visibility='invisible')
	{
		if (empty($value) or $visibility===false)
		{
			DBManager::Query("DELETE FROM youser_attributes WHERE youser_id=? AND name=?",
				$this->id,
				$attribute
			);
		}
		else
		{
			DBManager::Query("INSERT INTO youser_attributes (youser_id, name, value, visibility) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE value=VALUES(value), visibility=VALUES(visibility)",
				$this->id,
				$attribute,
				$value,
				$visibility
			);
		}
		return $this;
	}

	public function get_attribute($attribute = null, $visibility=false)
	{
		if ($this->attributes === null)
		{
			$this->attributes = DBManager::Query("SELECT name, value, visibility FROM youser_attributes WHERE youser_id=?",
				$this->id
			)->to_array('name', array('value', 'visibility'));
		}

		if ($attribute === null)
		{
			return $this->attributes;
		}

		return isset($this->attributes[$attribute])
			? $this->attributes[$attribute]
			: false;
	}

	public function getProfile()
	{
		return new YouserProfile($this->id);
	}

	private function isValid()
	{
		return !(
			empty($this->nickname) or
			empty($this->email) or
			empty($this->role) or
			empty($this->password) or
			empty($this->secret)
		);
	}

	public function to_array()
	{
		return array(
			'id'=>$this->id,
			'nickname'=>$this->nickname,
			'password'=>$this->password,
			'email'=>$this->email,
			'role'=>$this->role,
			'secret'=>$this->secret,
			'blocked'=>$this->blocked,
			'visible'=>$this->visible,
			'language'=>$this->language
		);
	}

	public static function Validate($alias, $password=false)
	{
		if ($password === false)
		{
			$result = DBManager::Get()->query("SELECT youser_id FROM yousers WHERE MD5(CONCAT(youser_id, '|', secret))=? LIMIT 1",
				$alias
			);
		}
		else
		{
			$result = DBManager::Get()->query("SELECT youser_id FROM yousers WHERE ? IN (nickname, email) AND password=? LIMIT 1",
				$alias,
				md5($password) // TODO: Passwort
			);
		}

		if ($result->is_empty())
		{
			return false;
		}
		return $result->fetch_item('youser_id');
	}

	public static function Login($youser_id, $duration=0)
	{
		Event::Dispatch('alert', 'Youser:Login', $youser_id);

		Session::Set('Youser', 'id', $youser_id);
		if ($duration>0)
		{
			Session::Prolong('+'.$duration);
		}
	}

	public static function Logout()
	{
		Session::Clear('Youser', 'id');
		Session::Prolong(0);
	}

	public static function Beat()
	{
		if (($youser=self::Get())!==false)
		{
			DBManager::Get()->query("UPDATE yousers SET lastaction=NOW() WHERE youser_id=?",
				$youser->id
			);
		}
	}

	public static function GetUsersOnline($minutes=5)
	{
		return DBManager::Get()->query("SELECT youser_id FROM yousers WHERE visible='yes' AND TIMESTAMPADD(MINUTE, ?, lastaction)>NOW()", $minutes)->to_array(null, ':first');
	}
	
	public static function GetPeopleOnline()
	{
		return DBManager::Get('devices')->query("SELECT yousers_online FROM online;")->fetch_item();
	}

	public static function GetYouserCount()
	{
		return DBManager::Get()->query("SELECT COUNT(*) FROM yousers")->fetch_item();
	}

	public static function Id()
	{
		return Session::Get('Youser', 'id');
	}

	// TODO: Optimization by preloading a bundle
	public static function Get($youser_id=null, $by_nickname=false)
	{
		if (is_array($youser_id))
		{
			return array_map(array('self', 'Get'), $youser_id);
		}
		if ($youser_id===null)
		{
			$youser_id = Youser::Id();
		}
		if ($youser_id===null)
		{
			return false;
		}
		return $by_nickname
			? new Youser(null, $youser_id)
			: new Youser($youser_id);
	}

	public static function GetByEmail($email)
	{
		return self::Get(DBManager::Get()->query("SELECT youser_id FROM yousers WHERE email=?", $email)->fetch_item());
	}

	public static function GetRole()
	{
		$youser = self::Get();
		return $youser===false
			? 'none'
			: $youser->role;
	}

	public static function Is()
	{
		$roles = array();

		$arguments = func_get_args();
		foreach ($arguments as $argument)
		{
			$roles = array_merge($roles, explode(',', $argument));
		}

		$youser = self::Get();
		return $youser===false
			? in_array('none', $roles)
			: in_array($youser->role, $roles);
	}

	public static function GetBundle($skip=null, $limit=null)
	{
		$query = "SELECT yousers.youser_id, nickname, email, yousers.role, GROUP_CONCAT(youser_permission.role) AS roles, UNIX_TIMESTAMP(register_timestamp) AS register_timestamp, blocked, IF(confirmations.youser_id IS NULL, 1, 0) AS activated, lastaction>TIMESTAMPADD(MINUTE, -?, NOW()) AS online, language, visible FROM yousers LEFT JOIN confirmations ON (yousers.youser_id=confirmations.youser_id AND confirmations.scope='activation') LEFT JOIN youser_permission ON (yousers.youser_id=youser_permission.youser_id AND youser_permission.role IS NOT NULL) GROUP BY youser_id ORDER BY register_timestamp ASC";
		return DBManager::Get()->skip($skip)->limit($limit)->query($query, Config::Get('system:online_duration'))->to_array();
	}

	private static function FieldExists($field, $value, $exclude='')
	{
		if (!is_array($exclude))
		{
			$exclude = array($exclude);
		}
		return DBManager::Get()->query("SELECT 1 FROM yousers WHERE {$field}=TRIM(?) AND {$field} NOT IN (?) LIMIT 1",
			$value,
			$exclude
		)->is_present();
	}

	public static function NicknameExists($nickname, $exclude='')
	{
		static $cache = array();
		if (!isset($cache[$nickname]))
		{
			$cache[$nickname] = self::FieldExists('nickname', $nickname, $exclude);
		}
		return $cache[$nickname];
	}

	public static function EmailExists($email, $exclude='')
	{
		return self::FieldExists('email', $email, $exclude);
	}

	public static function GetRoles()
	{
		$roles = array();

		$result = DBManager::Get()->query("DESC yousers");
		while ($row = $result->fetchArray())
		{
			if ($row['Field']!='role')
			{
				continue;
			}
			eval('$roles = '.str_replace(array('enum(', 'set('), 'array(', $row['Type']).';');
		}
		$result->release();

		return $roles;
	}

	public static function Delete($youser_id)
	{
		DBManager::Get()->query("DELETE FROM yousers WHERE youser_id=? LIMIT 1", $youser_id);
		Confirmation::Delete($youser_id);

		Event::Dispatch('alert', 'Youser:Delete', $youser_id);
	}

	public static function May($permission)
	{
		$arguments = func_get_args();
		$permission = array_pop($arguments);
		$youser_id = empty($arguments) ? Youser::Id() : array_pop($arguments);

		return YouserPermissions::Get( $youser_id )->granted($permission);
	}

	/*
	 * Should deprecate Is()
	 */
	public static function HasRole($role)
	{
		$arguments = func_get_args();
		$role = array_pop($arguments);
		$youser_id = empty($arguments) ? Youser::Id() : array_pop($arguments);

		return YouserPermissions::Get( $youser_id )->is($role);
	}
}
?>