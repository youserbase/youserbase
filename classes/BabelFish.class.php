<?php
class BabelFish
{
	private static $cache = array();
	private static $untranslated = array();

	private static $languages = null;
	private static $language = null;
	private static $default_language = 'uk';

	public static function SetLanguage($language=null, $prevent_cookie = false)
	{
		if ($language === null and self::$language !== null)
		{
			return;
		}

		if ($language === null and Session::Get('language') !== null)
		{
			$language = Session::Get('language');
		}
		if ($language === null and Youser::Get() !== false)
		{
			$language = Youser::Get()->language;
		}
		if ($language === null and Cookie::Get('language'))
		{
			$language = Cookie::Get('language');
		}
		if ($language === null and !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $user_language)
			{
				list($user_language, $quality) = explode(';', $user_language.';', 2);
				if (in_array($user_language, self::GetLanguages()))
				{
					$language = $user_language;
					break;
				}
			}
		}
		if ($language === null)
		{
			$language = self::$default_language;
		}

		self::$language = $language;
		Session::Set('language', $language);

		if (!isset(self::$cache[self::$language]))
		{
			self::$cache[self::$language] = array();
		}
		if (!isset(self::$untranslated[self::$language]))
		{
			self::$untranslated[self::$language] = array();
		}

		if (!$prevent_cookie and (!Cookie::Get('language') or $language!=Cookie::Get('language')))
		{
			Cookie::Set('language', self::$language, '+'.(365*24*60*60));
		}
	}

	public static function GetLanguage()
	{
		self::SetLanguage();
		return self::$language;
	}

	public static function Get($phrase_id, $language=null)
	{
		$old_language = self::GetLanguage();

		self::SetLanguage($language, true);

		$return_array = is_array($phrase_id);

		$phrase_id = array_unique((array)$phrase_id);

		$load_ids = array_diff($phrase_id, array_keys((array)self::$cache[self::$language]));

		if (!empty($load_ids))
		{
			$loaded_phrases = DBManager::Get()->query("SELECT phrase_id, phrase, language FROM phrases WHERE language IN ('*', ?, ?) AND phrase_id IN (?) ORDER BY language='*' DESC, language=? ASC",
				self::$default_language,
				self::$language,
				$phrase_id,
				self::$language
			)->to_array('phrase_id', 'phrase');

			foreach ($loaded_phrases as $id=>$value)
			{
				self::$cache[self::$language][$id] = $value;
			}
		}

		$return = array();
		foreach ($phrase_id as $id)
		{
			if (isset(self::$cache[self::$language][$id]))
			{
				$return[$id] = self::$cache[self::$language][$id];
			}
			else
			{
				$return[$id] = $id;
			}
		}

		self::$untranslated[self::$language] = array_unique(
			array_merge(
				(array)self::$untranslated[self::$language],
				array_diff($phrase_id, array_keys(self::$cache[self::$language]))
			)
		);

		self::SetLanguage($old_language, true);

		return $return_array
			? $return
			: reset($return);
	}

	public static function ReverseLookup($phrase, $prefix='', $as_array = false)
	{
		$prefix = rtrim($prefix, '%').'%';
		if (is_array($phrase))
		{
			return DBManager::Get()->query("SELECT phrase_id, phrase FROM phrases WHERE phrase IN (?) AND phrase_id LIKE ? GROUP BY phrase", $phrase, $prefix)->to_array('phrase', 'phrase_id');
		}

		if (!isset(self::$cache['REVERSE'][md5($phrase)]))
		{
			self::$cache['REVERSE'][md5($phrase)] = DBManager::Get()->query("SELECT phrase_id FROM phrases WHERE REPLACE(phrase, '_', ' ')=REPLACE(?, '_', ' ') AND phrase_id LIKE ?", $phrase, $prefix)->to_array();
		}
		return $as_array
			? self::$cache['REVERSE'][md5($phrase)]
			: reset(self::$cache['REVERSE'][md5($phrase)]);
	}

	public static function Search($phrase, $language=null)
	{
		if ($language===null)
		{
			$language = self::GetLanguage();
		}

		$query = "SELECT phrase, phrase_id FROM phrases WHERE phrase LIKE '%";
		$query .= implode("%' OR phrase LIKE '%", (array)$phrase);
		$query .= "%' AND language IN ('*', 'uk', ?)";

		$phrases = DBManager::Get()->query($query, $language)->to_array();

		foreach ($phrases as $result)
		{
			self::$cache[$language][$result['phrase_id']] = $result['phrase'];
		}

		return $phrases;
	}

	public static function GetLanguages()
	{
		if (self::$languages===null)
		{
			self::$languages = DBManager::Get()->query("SELECT language FROM yousers GROUP BY language ORDER BY COUNT(*) DESC, language ASC")->to_array(null, 'language');
			self::$languages = array_merge(self::$languages, DBManager::Get()->query("SELECT DISTINCT language FROM phrases WHERE NOT language IN ('*', ?) ORDER BY language ASC", self::$languages)->to_array(null, 'language'));
		}
		return self::$languages;
	}

	public static function GetSpokenLanguages()
	{
		return DBManager::Get()->query("SELECT DISTINCT language FROM phrases WHERE language NOT IN ('*') ORDER BY language ASC")->to_array(null, 'language');
	}

	public static function GetPhraseCount($language=null)
	{
		$query = "SELECT COUNT(*) FROM phrases";
		if ($language!==null)
		{
			$query .= " WHERE language=?";
		}
		return DBManager::Get()->query($query, $language)->fetch_item();
	}

	public static function GetPhrases($language, $skip=null, $limit=null)
	{
		return DBManager::Get()->skip($skip)->limit($limit)->query("SELECT phrase_id, phrase FROM phrases WHERE language=? ORDER BY phrase_id ASC", $language)->to_array('phrase_id', 'phrase');
	}

	public static function GetMissing($language, $skip=null, $limit=null)
	{
		return DBManager::Get()->skip($skip)->limit($limit)->query("SELECT phrase_id FROM phrases GROUP BY phrase_id HAVING FIND_IN_SET(?, GROUP_CONCAT(language SEPARATOR ','))=0", $language)->to_array(null, 'phrase_id');
	}

	public static function GetMissingCount($language)
	{
		return DBManager::Get()->query("SELECT COUNT(*) FROM (SELECT 1 FROM phrases GROUP BY phrase_id HAVING FIND_IN_SET(?, GROUP_CONCAT(language SEPARATOR ','))=0) AS foo", $language)->fetch_item();
	}

	public static function DeletePhrase($language, $phrase_id)
	{
		return DBManager::Get()->query("DELETE FROM phrases WHERE language=? AND phrase_id=?", $language, $phrase_id);
	}

	public static function InsertPhrase($language, $phrase_id, $phrase, $youser_id=null)
	{
		return DBManager::Get()->query("INSERT INTO phrases (language, phrase_id, phrase, youser_id, last_update) VALUES (?, ?, ?, ?, NOW())",
			$language,
			$phrase_id,
			$phrase,
			$youser_id
		);
	}

	public static function Update($language, $phrase_id, $value, $youser_id)
	{
		return DBManager::Get()->query("INSERT INTO phrases (language, phrase_id, phrase, youser_id, last_update) VALUES (?, ?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE phrase=VALUES(phrase), youser_id=VALUES(youser_id), last_update=VALUES(last_update)",
			$language,
			$phrase_id,
			$value,
			$youser_id
		);
	}

	public static function UpdatePhrase($language, $phrase_id, $value, $new_phrase_id=null, $youser_id=null)
	{
		if ($new_phrase_id!==null)
		{
			return DBManager::Get()->query("UPDATE phrases SET phrase_id=?, phrase=?, youser_id=?, last_update=NOW() WHERE phrase_id=? AND language=?",
				$new_phrase_id,
				$value,
				$youser_id,
				$phrase_id,
				$language
			);
		}
		return DBManager::Get()->query("INSERT INTO phrases (phrase_id, language, phrase, youser_id, last_update) VALUES (?,?,?,?, NOW()) ON DUPLICATE KEY UPDATE phrase=VALUES(phrase), youser_id=VALUES(youser_id), last_update=VALUES(last_update)",
			$phrase_id,
			$language,
			$value,
			$youser_id
		);
	}

	public static function GetUntranslated()
	{
		return self::$untranslated[self::$language];
	}

	public static function GetCache()
	{
		return self::$cache[self::$language];
	}

	public static function Confirm($phrase_id, $language, $youser_id)
	{
		return DBManager::Get()->query("UPDATE phrases SET confirmed_timestamp=NOW(), confirmed_youser_id=? WHERE phrase_id=? AND language=?",
			$youser_id,
			$phrase_id,
			$language
		);
	}
}
?>