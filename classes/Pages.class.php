<?php
class Pages
{
	public static function GetPage($page_id, $language, $revision = null)
	{
		return $revision === null
			? DBManager::Get()->limit(1)->query("SELECT youser_id, revision, UNIX_TIMESTAMP(timestamp) AS timestamp, contents, hash FROM pages WHERE page_id=UPPER(?) AND language IN (?, 'uk') ORDER BY language='uk' ASC, revision DESC", $page_id, $language)->fetch_array()
			: DBManager::Get()->limit(1)->query("SELECT youser_id, revision, UNIX_TIMESTAMP(timestamp) AS timestamp, contents, hash FROM pages WHERE page_id=UPPER(?) AND language IN (?, 'uk') AND revision=? ORDER BY language='uk' ASC", $page_id, $language, $revision)->fetch_array();
	}

	public static function GetLatestRevision($page_id, $language)
	{
		return DBManager::Query("SELECT MAX(revision) FROM pages WHERE page_id=UPPER(?) AND language=?",
			$page_id,
			$language
		)->fetch_item();
	}

	public static function GetLatestAuthor($page_id, $language)
	{
		return DBManager::Get()->limit(1)->query("SELECT youser_id FROM pages WHERE page_id=UPPER(?) AND language=? ORDER BY revision DESC",
			$page_id,
			$language
		)->fetch_item();
	}

	public static function UpdatePage($page_id, $language, $contents, $youser_id)
	{
		DBManager::Get()->query("INSERT INTO pages (page_id, language, contents, hash, youser_id, timestamp) VALUES (UPPER(?), ?, ?, ?, ?, NOW())",
			$page_id,
			$language,
			$contents,
			md5($contents),
			$youser_id
		);
	}
}
?>