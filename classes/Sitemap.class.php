<?php
class Sitemap
{
	private static $cache = null;

	public static function &GetCache()
	{
		if (self::$cache === null)
		{
			self::$cache = DBManager::Get()->query("SELECT site_id, parent_site_id, plugins, content_plugins_before, content_plugins_after, navigation, tabs, meta_description_phrase_id, meta_keywords_phrase_id FROM sitemap")->to_array('site_id', array('parent_site_id', 'plugins', 'content_plugins_before', 'content_plugins_after', 'navigation', 'tabs', 'meta_description_phrase_id', 'meta_keywords_phrase_id'));
			foreach (self::$cache as $site_id=>$site)
				foreach (array('plugins','content_plugins_before','content_plugins_after','tabs') as $scope)
					self::$cache[$site_id][$scope] = array_filter(explode(',', $site[$scope]));
		}
		return self::$cache;
	}

	public static function GetParentSite($site_id)
	{
		$cache = self::GetCache();
		return isset($cache[$site_id]['parent_site_id'])
			? $cache[$site_id]['parent_site_id']
			: null;
	}

	public static function GetParentSites()
	{
		$result = array();
		foreach (self::GetCache() as $index=>$site)
		{
			$result[$index] = $site['parent_site_id'];
		}
		return $result;
	}

	public static function ClearParentSites()
	{
		$cache =& self::GetCache();
		foreach ($cache as $id=>$site)
		{
			$cache[$id]['parent_site_id'] = null;
		}

		DBManager::Get()->query("UPDATE sitemap SET parent_site_id=NULL");
	}

	public static function UpdateParentSite($site_id, $parent_site_id)
	{
		$cache =& self::GetCache();
		$cache[$site_id]['parent_site_id'] = $parent_site_id;

		DBManager::Get()->query("INSERT INTO sitemap (site_id, parent_site_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE parent_site_id=VALUES(parent_site_id)",
			$site_id,
			$parent_site_id
		);
	}

	private static function MapPluginScope($scope)
	{
		$mapping = array(
			'before' => 'content_plugins_before',
			'after' => 'content_plugins_after',
		);
		return isset($mapping[$scope])
			? $mapping[$scope]
			: 'plugins';
	}

	public static function GetPlugins($site_id, $scope='plugins')
	{
		$cache = self::GetCache();
		return isset($cache[$site_id][self::MapPluginScope($scope)])
			? $cache[$site_id][self::MapPluginScope($scope)]
			: array();
	}

	public static function UpdatePlugins($site_id, $plugins, $scope='plugins')
	{
		$scope = self::MapPluginScope($scope);

		$cache =& self::GetCache();
		$cache[$site_id][$scope] = $plugins;

		DBManager::Get()->query("INSERT INTO sitemap (site_id, {$scope}) VALUES (?, ?) ON DUPLICATE KEY UPDATE {$scope}=VALUES({$scope})",
			$site_id,
			implode(',', $plugins)
		);
	}

	public static function GetNavigation($site_id)
	{
		$cache = self::GetCache();
		return isset($cache[$site_id]['navigation'])
			? $cache[$site_id]['navigation']
			: null;
	}

	public static function UpdateNavigation($site_id, $navigation_id)
	{
		$cache =& self::GetCache();
		$cache[$site_id]['navigation'] = $navigation_id;

		DBManager::Get()->query("INSERT INTO sitemap (site_id, navigation) VALUES (?, ?) ON DUPLICATE KEY UPDATE navigation=VALUES(navigation)",
			$site_id,
			$navigation_id
		);
	}

	public static function GetTabs($site_id)
	{
		$cache = self::GetCache();
		if (!isset($cache[$site_id]) or empty($cache[$site_id]['tabs']))
		{
			return array();
		}

		$actions = FrontController::GetAvailableActions();

		$tabs = array();
		foreach ((array)$cache[$site_id]['tabs'] as $tab_id)
		{
			$tabs[$tab_id] = $actions[$tab_id];
		}
		return $tabs;
	}

	public static function SetTabs($site_id, $tabs)
	{
		$cache =& self::GetCache();
		$cache[$site_id]['tabs'] = $tabs;

		DBManager::Get()->query("INSERT INTO sitemap (site_id, tabs) VALUES (?, ?) ON DUPLICATE KEY UPDATE tabs=VALUES(tabs)",
			$site_id,
			implode(',', $tabs)
		);
	}

	public function GetMetaDescription($site_id, $language = false)
	{
		$cache = self::GetCache();
		if (!isset($cache[$site_id]))
		{
			return false;
		}
		return BabelFish::Get($cache[$site_id]['meta_description_phrase_id'], $language);
	}

	// TODO: Adapt to cache
	public function SetMetaDescriptionPhraseId($site_id, $phrase_id)
	{
		return DBManager::Get()->query("INSERT INTO sitemap (site_id, meta_description_phrase_id) VALUES (?, TRIM(?)) ON DUPLICATE KEY UPDATE meta_description_phrase_id=VALUES(meta_description_phrase_id)", $site_id, $phrase_id);
	}

	public function GetMetaKeywords($site_id, $language = false)
	{
		$cache = self::GetCache();
		if (!isset($cache[$site_id]))
		{
			return false;
		}
		return BabelFish::Get($cache[$site_id]['meta_keywords_phrase_id'], $language);
	}

	// TODO: Adapt to cache
	public function SetMetaKeywordsPhraseId($site_id, $phrase_id)
	{
		return DBManager::Get()->query("INSERT INTO sitemap (site_id, meta_keywords_phrase_id) VALUES (?, TRIM(?)) ON DUPLICATE KEY UPDATE meta_keywords_phrase_id=VALUES(meta_keywords_phrase_id)", $site_id, $phrase_id);
	}
}
?>