<?php
class comment_handler
{
	
	public function find_links($comment, $translation = false)
	{
		if(empty($comment))
		{
			return false;
		}
		if(!Youser::Is('administrator', 'root', 'god') || $translation === true)
		{
			$comment = strip_tags($comment);
		
			$regex = '/(https?:\/\/|ftp:\/\/|www.)(\w+.\w+)\/?(\S+)?/';
			preg_match_all($regex, $comment, $matches);
			$http = '';
			foreach ($matches[0] as $key => $match)
			{
				if(strpos($match, 'http://') === false)
				{
					$http = 'http://';
				}
				$comment = str_replace($match, '<a href="'.$http.$match.'" target="_blank">'.$match.'</a>', $comment);
			}
		}
		return nl2br($comment);
	}
	
	public function check_yousername($youser_name)
	{
		$isset = DBManager::Get()->query("SELECT COUNT(youser_id) FROM yousers WHERE nickname LIKE ?", $youser_name)->fetch_item();
		return $isset;
		
	}
	/**
	 * Get Comments by device_id, language will be ignored If a category is set, only comments with this category will be loaded
	 *
	 * @param $device_id 
	 * @param $language
	 * @param int $skip
	 * @param int $limit
	 * @param $order
	 * @param $offensive_limit
	 * @return 2-dimensional array containing comments with comments_id, youser_id, timestamp, comment, category, offensive_counts
	 */
	public static function get_comments($device_id, $language, $skip, $limit, $order, $offensive_limit)
	{
		$comments = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id, comments_id, comment, website, language, category, youser_id, positive, negative, type, guest_name, UNIX_TIMESTAMP(timestamp) as timestamp, offensive_counts FROM comments WHERE device_id = ? AND offensive_counts < ? AND granted = 1 ORDER BY timestamp $order", $device_id, $offensive_limit)->to_array();
		return $comments;
	}
	
	public static function get_search_comments($device_id, $needle, $skip, $limit, $order, $offensive_limit)
	{
		$comments = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id, comments_id, comment, website, language, category, youser_id, positive, negative, UNIX_TIMESTAMP(timestamp) as timestamp, offensive_counts FROM comments WHERE device_id = ? AND granted = 1 AND offensive_counts < ? $needle ORDER BY timestamp $order", $device_id, $offensive_limit)->to_array();
		return $comments;
	}

	/**
	 * Get offensive comments by device_id
	 *
	 * @param $device_id
	 * @param $limit
	 * @param $skip
	 * @return 2-dimensional array containing comments with comments_id, youser_id, timestamp, comment, category, offensive_counts
	 */
	public static function get_offensive_comments($device_id, $limit = 25, $skip = 0)
	{
		$comments = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT comments_id, youser_id, timestamp, comment, category, offensive_counts FROM comments WHERE device_id = ? AND offensive_counts > 0 ORDER BY timestamp DESC", $device_id)->to_array();
		return $comments;
	}
	
	/**
	 * Get deleted comments
	 *
	 * @param $device_id
	 * @param $limit
	 * @param $skip
	 * @return 2-dimensional array containing comments with comments_id, youser_id, timestamp, comment, category, offensive_counts
	 */
	public static function get_deleted_comments($limit = 25, $skip = 0)
	{
		$comments = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT comments_id, youser_id, timestamp, comment, category, offensive_counts FROM offensive_comments ORDER BY timestamp DESC")->to_array();
		return $comments;
	}
	
	/**
	 * Get the number of comments. If no category is submitted it will get the number of comments per language
	 *
	 * @param unknown_type $categories
	 * @param unknown_type $device_id
	 * @return unknown
	 */
	public static function get_comment_count($device_id, $categories = null, $lang = null)
	{
		$youser_lang = BabelFish::GetLanguage();
		if($categories !== null && $lang === null)
		{
			$count = DBManager::Get('devices')->query("SELECT language, COUNT(language) AS counter FROM comments WHERE device_id = ? AND categories IN (".str_repeat('?,', count($categories)).") GROUP BY language", $device_id, implode(', ', $categories))->to_array();
		}
		else if($categories === null && $lang !== null)
		{
			$count = DBManager::Get('devices')->query("SELECT language, COUNT(language) AS counter FROM comments WHERE device_id = ? AND language IN (".str_repeat('?,', count($lang)).") GROUP BY language", $device_id, implode(', ', $lang))->to_array();
		}
		else 
		{
			$count = DBManager::Get('devices')->query("SELECT language, COUNT(language) AS counter FROM comments WHERE device_id = ? AND language != ? GROUP BY language;", $device_id, $youser_lang)->to_array();
		}
		return $count;
	}
	
	
	/**
	 * Get a comment by its id
	 *
	 * @param unknown_type $comment_id
	 * @return unknown
	 */
	public static function get_yousercomment($comment_id)
	{
		return DBManager::Get('devices')->query("SELECT comment FROM comments WHERE comments_id = ?", $comment_id)->fetch_item();
	}
	
	/**
	 * Insert a new comment into DB.
	 * If comments_id is set, an existing comment will be updated.
	 * If offensive is set, the comment will change its offensive status to this
	 *
	 * @param $device_id
	 * @param string $comments_id
	 * @param int $offensive
	 */
	public static function set_comment($device_id, $comments_id = null, $offensive = null)
	{
		$category = 'COMMON';
		if(isset($_REQUEST['category']))
		{
			$category = strtoupper($_REQUEST['category']);
		}
		if(!isset($_REQUEST['comment']))
		{
			return false;
		}
		if($offensive === null)
		{
			$offensive = 0;	
		}
		$comment = utf8_encode(saveSheet::clean(utf8_decode($_REQUEST['comment'])));
		$comments = new comments();
		if($comments_id !== null)
		{
			$comments->comments_id = $comments_id;
		}
		$comments->comment = $comment;
		$comments->category = $category;
		$comments->language = BabelFish::GetLanguage();
		$comments->device_id = $device_id;
		$comments->offensive_counts = $offensive;
		$comments->youser_id = Youser::Id();
		$comments->timestamp = "NOW()";
		$comments->save();
	}
	
	/**
	 * Update the offensive-status of a comment
	 *
	 * @param $comments_id
	 * @param $device_id
	 */
	public static function update_offensive($comments_id, $device_id)
	{
		$offensive_count = DBManager::Get('devices')->query("SELECT offensive_counts FROM comments WHERE comments_id = ?", $comments_id)->fetch_item();
		if(!isset($_REQUEST['offensive']))
		{
			$offensive_count++;
			DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = ? WHERE comments_id =?;", $offensive_count, $comments_id);
		}
		else if($_REQUEST['offensive'] == -1)
		{
			DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = 0 WHERE comments_id =?;", $comments_id);
		}
		else if($_REQUEST['offensive'] == 3)
		{
			if($offensive_count >= 1)
			{
				self::delete_comment($comments_id);
			}
		}
		else 
		{
			$offensive_count++;
			DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = ? WHERE comments_id =?;", $offensive_count, $comments_id);
		}
	}
	
	/**
	 * Deletes a comment
	 *
	 * @param $comment_id
	 */
	public static function delete_comment($comment_id)
	{
		DBManager::Get('devices')->query("INSERT INTO offensive_comments SELECT * FROM comments WHERE comments_id = ?;", $comment_id);
		DBManager::Get('devices')->query("DELETE FROM comments WHERE comments_id = ?", $comment_id);;
	}
}
?>