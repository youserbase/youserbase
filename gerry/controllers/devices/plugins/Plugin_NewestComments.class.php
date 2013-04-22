<?php
class Plugin_NewestComments extends Plugin
{
	public static $options = array(
		'display_limit:range:1,10',
		'max_comment_length:range:10,500,10'
	);

	public function skip_comments()
	{
		Session::Set('skip_new_comments', $_GET['skip']);
	}

	public function fill_template(&$template)
	{
		$limit = $this->get_config('display_limit');

		$skip = 0+Session::Get('skip_new_comments');

		$comments = DBManager::Get('devices')->limit($limit)->skip($skip)->query("SELECT device_id, youser_id, comment, UNIX_TIMESTAMP(timestamp) AS timestamp, type FROM comments WHERE granted = 1 AND offensive_counts < 2 ORDER BY timestamp DESC", BabelFish::GetLanguage())->to_array();
		$total = DBManager::Get('devices')->query("SELECT COUNT(*) FROM comments WHERE language = ?;", BabelFish::GetLanguage())->fetch_item();
		if(empty($comments))
		{
			return false;
		}
		$template->assign('max_comment_length', $this->get_config('max_comment_length'));
		$template->assign('total', $total);
		$template->assign('skip', $skip);
		$template->assign('limit', $limit);
		$template->assign('comments', $comments);
		return true;
	}
}
?>
