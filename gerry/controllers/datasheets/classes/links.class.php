<?php
class links
{
	public static function get_links($device_id, $category = null, $language = null, $skip = 0, $limit = 5)
	{
		$skip = 0;
		$limi = 5;
		if($language == null)
		{
			$language = BabelFish::GetLanguage();
		}
		else
		{
			$links = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT * FROM device_links WHERE device_id = ? AND language_id != ? ORDER BY timestamp DESC", $device_id, $language)->to_array();
			$return = array();
			foreach ($links as $link)
			{
				if(!isset($return[$link['language_id']]))
				{
					$return[$link['language_id']] = array($link);
				}
				else 
				{
					array_push($return[$link['language_id']], $link);
				}
			}
			return $return;
		}
		if($category == null)
		{
			$links = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT * FROM device_links  WHERE device_id = ? AND language_id = ? ORDER BY timestamp DESC", $device_id, $language)->to_array();
			return $links;
		}
		else
		{
			$links = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT * FROM device_links WHERE device_id = ? AND language_id = ? AND category = ? ORDER BY timestamp DESC", $device_id, $language, $category)->to_array();
			return $links;
		}
	}
	
	public static function get_helpfull($links)
	{
		$helpfull = array();
		foreach ($links as $link)
		{
			$helpfull[$link['device_links_id']] = DBManager::Get('devices')->query("SELECT SUM(helpfull), SUM(not_helpfull) FROM device_links_helpfull WHERE device_links_id = ?;", $link['device_links_id'])->to_array();
		}
		return $helpfull;
	}
		
	public static function set_links($device_id, $device_id_int)
	{
		$links = new device_links();
		$links->device_id = $device_id;
		$links->device_id_int = $device_id_int;
		if(isset($_REQUEST['link']))
		{
			$link = self::is_link($_REQUEST['link']);
			if($link != false)
			{
				$links->link = $link;
			}
			else
			{
				Dobber::ReportError("INVALID_LINK: ".$_REQUEST['link']);
				return false;
			}
		}
		$links->title = $_REQUEST['title'];
		$links->page_type = $_REQUEST['page_type'];
		$links->content_type = $_REQUEST['content_type'];
		$links->language_id = $_REQUEST['link_language'];
		$links->youser_id = Youser::Id();
		$links->timestamp = 'NOW()';
		if(isset($_REQUEST['device_links_id']))
		{
			$links->device_links_id = $_REQUEST['device_links_id'];
		}
		$links->save();
	}

	
	private static function is_link($link)
	{
		if(empty($link))
		{
			return false;
		}
		if(strpos($link, 'http://') === false)
		{
			$link = 'http://'.$link;
		}
		if(strpos($link, ' ') !== false)
		{
			return false;
		}
		if(get_headers($link) === false)
		{
			return false;
		}
		return $link;
	}
}
?>