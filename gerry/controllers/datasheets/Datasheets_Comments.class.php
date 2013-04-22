<?php
class Datasheets_Comments extends Controller
{
	private $offensive_limit = 2;
	private $categories = array('DISCUSSION', 'TIPS_TRICKS', 'HELPFULL_LINKS', 'TECHNOLOGY', 'SOFTWARE');
	private $skip = 0;
	private $limit = 25;
	private $order_by = 'DESC';

	public function Index()
	{
		$compare = 'false';
		if (isset($_REQUEST['device_id'])){
			$device_id = $_REQUEST['device_id'];
			$compare = 'false';
		}
		else if(!isset($_REQUEST['device_id']) && isset($_REQUEST['compare_id'])){
			$device_id = $_REQUEST['compare_id'];
			$compare = 'true';
		}
		$language = array(BabelFish::GetLanguage());
		$selected_categories = 'all';
		$page = 0;
		if(isset($_REQUEST['page']))
		{
			$page = $_REQUEST['page'];
		}
		if(Session::Get('Comments_Limit'))
		{
			$this->limit = Session::Get('Comments_Limit');
		}
		if(isset($_REQUEST['order_by']))
		{
			if(strpos($_REQUEST['order_by'], 'DESC') !== false)
			{
				Session::Set('Comments_OrderBY', 'DESC');
			}
			else
			{
				Session::Set('Comments_OrderBY', 'ASC');
			}
		}
		if(Session::Get('Comments_OrderBY'))
		{
			if(strpos(Session::Get('Comments_OrderBY'), 'DESC') !== false)
			{
				$this->order_by = 'DESC';
			}
			else
			{
				$this->order_by = 'ASC';
			}
		}
		$this->skip = $this->limit*$page;
		if(isset($_REQUEST['comment_category']))
		{
			$selected_categories = $_REQUEST['comment_category'];
		}
		$needle = '';
		if($selected_categories === 'all' && !isset($_REQUEST['needle']))
		{
			$comment_lang = DBManager::Get('devices')->query("SELECT language, COUNT(language) AS counter FROM comments WHERE device_id = ? AND offensive_counts <= ? GROUP BY language", $device_id, $this->offensive_limit)->to_array('language', 'counter');
			$categories = DBManager::Get('devices')->query("SELECT category, COUNT(category) AS counter FROM comments WHERE device_id = ? AND language IN (?) AND offensive_counts <= ? GROUP BY category ORDER BY timestamp ASC", $device_id, $language, $this->offensive_limit)->to_array();
			$comment_count = DBManager::Get('devices')->query("SELECT COUNT(comments_id) FROM comments WHERE device_id = ? AND offensive_counts <= ?", $device_id, $this->offensive_limit)->fetch_item();
			$comments = comment_handler::get_comments($device_id, $language, $this->skip, $this->limit, $this->order_by, $this->offensive_limit);
		}
		elseif(isset($_REQUEST['needle']))
		{
			$needle = '';
			if(strlen($_REQUEST['needle']) >= 2)
			{
				$needle = explode(' ', $_REQUEST['needle']);
				$needle = " AND comment LIKE '%".implode("%' AND comment LIKE '%", $needle)."%' ";
			}
			$comment_lang = DBManager::Get('devices')->query("SELECT language, COUNT(language) AS counter FROM comments WHERE device_id = ? AND offensive_counts < ? $needle GROUP BY language", $device_id, $this->offensive_limit)->to_array('language', 'counter');
			$categories = DBManager::Get('devices')->query("SELECT category, COUNT(category) AS counter FROM comments WHERE device_id = ? AND language IN (?) AND offensive_counts < ? $needle GROUP BY category ORDER BY timestamp ASC", $device_id, $language, $this->offensive_limit)->to_array();
			$comment_count = DBManager::Get('devices')->query("SELECT COUNT(comments_id) FROM comments WHERE device_id = ? AND offensive_counts < ? {$needle}", $device_id, $this->offensive_limit)->fetch_item();
			$comments = comment_handler::get_search_comments($device_id, $needle, $this->skip, $this->limit, $this->order_by, $this->offensive_limit);
		}

		$template = $this->get_template(true);
		$template->assign('compare', $compare);
		$template->assign('page', ceil($page));
		$template->assign('skip', $this->skip);
		$template->assign('limit', $this->limit);
		$template->assign('order_by', $this->order_by);
		$template->assign('used_language', $language);
		$template->assign('comment_count', $comment_count);
		$template->assign('comment_lang', $comment_lang);
		$template->assign('comments', $comments);
		$template->assign('categories', $categories);
		$template->assign('device_id', $device_id);
		$template->assign('selected_categories', $selected_categories);
		$template->assign('burn', $this->offensive_limit);
	}

	public function Limit()
	{
		if(isset($_REQUEST['limit']))
		{
			Session::Set('Comments_Limit', $_REQUEST['limit']);
		}
	}

	public function Edit()
	{
		$template = $this->get_template(true);
		$device_id = $_REQUEST['device_id'];
		$compare = $_REQUEST['compare'];
		$category = 'DISCUSSION';
		$page = 0;
		if(isset($_REQUEST['page'])){
			$page = $_REQUEST['page'];
		}
		if(isset($_REQUEST['category']))
		{
			$category = $_REQUEST['category'];
		}
		//$comment = array('comment' => '', 'category' => $category);
		$comment = '';
		$comments_id = null;
 		if (!empty($_REQUEST['comments_id']))
		{
			$comment = DBManager::Get('devices')->query("SELECT comment FROM comments WHERE comments_id = ?",$_REQUEST['comments_id'])->fetch_item();
		}
		if (isset($_REQUEST['comments_id']))
		{
			$comments_id = $_REQUEST['comments_id'];
		}
		$template->assign('compare', $compare);
		$template->assign('comments_id', $comments_id);
		$template->assign('device_id', $device_id);
		$template->assign('comment', $comment);
		$template->assign('page', $page);
		$template->assign('return_to', $_REQUEST['return_to']);
		//$template->assign('return_to_false', $_REQUEST['return_to_false']);
	}

	public function Save()
	{
		$blacklist = DBManager::Get('devices')->query("SELECT ip FROM blacklist;")->to_array();
		if(in_array($_SERVER['REMOTE_ADDR'], $blacklist))
		{
			if(in_array($_SERVER['REMOTE_ADDR'], $blacklist)){
			DBManager::Get('devices')->query("UPDATE blacklist SET counter = counter+1 WHERE ip = ?;", $_SERVER['REMOTE_ADDR']);
			}
			//Dobber::ReportNotice('You have been blacklisted');
			//return false;
		}
		$device_id = $_REQUEST['device_id'];
		$category = 'DISCUSSION';
		if(isset($_REQUEST['category'])){
			$category = strtoupper($_REQUEST['category']);
		}
		if(!empty($_REQUEST['comment']))
		{
			$comment = comment_handler::find_links($_REQUEST['comment'], $_REQUEST['return_to']);
		}
		else
		{
			Dobber::ReportNotice('EMPTYCOMMENT');
			if(isset($_REQUEST['return_to_false'])){
				FrontController::Relocate($_REQUEST['return_to_false']);
			}
			else FrontController::Relocate($_REQUEST['return_to']);
		}
		$youser_id = Youser::Id()
			? Youser::Id()
			: 107;
		/*else if(($youser_id = self::check_yousername()) == false)
		{
			return false;;
		}*/
		$last_comment = DBManager::Get('devices')->query("SELECT comment FROM comments WHERE youser_id = ? ORDER BY timestamp DESC LIMIT 0,1", $youser_id)->fetch_item();
		if($last_comment == $comment){
			Dobber::ReportError('MULTIPOST_INHIBITED');
			//if(isset($_REQUEST['return_to_false'])){
			//	FrontController::Relocate($_REQUEST['return_to_false']);
			FrontController::Relocate($_REQUEST['return_to']);
		}
		$type = 'device';
		if(!empty($_REQUEST['comments_id']))
		{
			DBManager::Get('devices')->query("UPDATE comments SET comment = ?, category = ? WHERE comments_id = ?", $comment, $category, $_REQUEST['comments_id']);
			if($_REQUEST['compare'] == 'true'){
				//Event::Dispatch('alert', 'Youser:EditCompareComment', $youser_id, $_REQUEST['device_id']);
				$type = 'compare';
			}else{ 
				$type = 'device';
				Event::Dispatch('alert', 'Youser:EditComment', $youser_id, $_REQUEST['device_id']);
			}
		}
		else
		{
			$email = null;
			$website = null;
			$name = null;
			$granted = 1;
			if(!Youser::Id()){
				if(!empty($_REQUEST['email']) && !empty($_REQUEST['name'])){
					$email = $_REQUEST['email'];
					$name = $_REQUEST['name'];
				} else {
					Dobber::ReportNotice('Not all information provided');
					return false;
				}
				if(!empty($_REQUEST['url'])){
					$website = $_REQUEST['url'];
					$granted = 0;
				}
			}else{
				if($_REQUEST['compare'] == 'true'){
					Event::Dispatch('alert', 'Youser:EditCompareComment', $youser_id, $_REQUEST['device_id']);
					$type = 'compare';
				}else{ 
					$type = 'device';
					Event::Dispatch('alert', 'Youser:WroteComment', $youser_id, $_REQUEST['device_id']);
				}
			}
			DBManager::Get('devices')->query("INSERT INTO comments (device_id, comment, category, language, youser_id, type, email, website, guest_name, granted) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $device_id, $comment, $category, BabelFish::GetLanguage(), $youser_id, $type, $email, $website, $name, $granted);
		}
		//Event::Dispatch('alert', 'Device:Commented', $youser_id, $device_id);
		$page = 0;
		if(Session::Get('Comments_OrderBY'))
		{
			$page = Session::Get('Comments_OrderBY') == 'DESC'
				? 0
				: $_REQUEST['page'];
		}
		FrontController::Relocate($_REQUEST['return_to']);
	}

	public static function check_yousername()
	{
		if(empty($_REQUEST['youser_id']))
		{
			return false;
		}
		if($yousername == null)
			$yousername = $_REQUEST['youser_id'];

		return comment_handler::check_yousername($yousername)<1
			? $_REQUEST['youser_id']
			: false;
	}

	public function check_mail($mail = null)
	{
		if(empty($_REQUEST['email']))
		{
			return 'ENTER_EMAIL';
		}
		if($mail == null)
			$mail = $_REQUEST['email'];

		return Youserinvitation::check_mail($mail)
			? true
			: 'INVALID_MAIL';
	}

	public function Burn($comments_id = null)
	{
		if(isset($_REQUEST['comments_id']))
		{
			DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = offensive_counts+1 WHERE comments_id = ?", $_REQUEST['comments_id']);
			FrontController::Relocate($_REQUEST['return_to']);
		}
		DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = offensive_counts+1 WHERE comments_id IN (?)", (array)$comments_id);
	}

	public function Ranking()
	{
		if(isset($_REQUEST['comments_id']))
		{
			DBManager::Get('devices')->query("UPDATE comments SET positive = positive + ?, negative = negative + ?, timestamp=timestamp WHERE comments_id = ?", (int)isset($_REQUEST['like']), (int)isset($_REQUEST['dislike']), $_REQUEST['comments_id']); // timestamp=timestamp?
		}
		if(isset($_REQUEST['return_to']))
		{
			FrontController::Relocate($_REQUEST['return_to']);
		}
	}

	public function Unburn($comments_id = null)
	{
		if(isset($_REQUEST['comments_id']))
		{
			DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = 0 WHERE comments_id = ?", $_REQUEST['comments_id']);
			FrontController::Relocate($_REQUEST['return_to']);
		}
		DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = 0 WHERE comments_id IN (?)", (array)$comments_id);
	}

	public function Delete($comments_id = null)
	{
		if(isset($_REQUEST['comments_id']))
		{
			DBManager::Get('devices')->query("INSERT INTO offensive_comments SELECT * FROM comments WHERE comments_id = ?", $_REQUEST['comments_id']);
			DBManager::Get('devices')->query("DELETE FROM comments WHERE comments_id = ?", $_REQUEST['comments_id']);
			FrontController::Relocate($_REQUEST['return_to']);
		}

		DBManager::Get('devices')->query("INSERT INTO offensive_comments SELECT * FROM comments WHERE comments_id IN (?)", (array)$comments_id);
		DBManager::Get('devices')->query("DELETE FROM comments WHERE comments_id IN (?)", (array)$comments_id);
	}

	public function Undelete($comments_id = null)
	{
		if(isset($_REQUEST['comments_id']))
		{
			DBManager::Get('devices')->query("INSERT INTO comments SELECT * FROM offensive_comments WHERE comments_id = ?", $_REQUEST['comments_id']);
			DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = 0 WHERE comments_id = ?", $_REQUEST['comments_id']);
			DBManager::Get('devices')->query("DELETE FROM offensive_comments WHERE comments_id = ?", $_REQUEST['comments_id']);
			FrontController::Relocate($_REQUEST['return_to']);
		}

		DBManager::Get('devices')->query("INSERT INTO comments SELECT * FROM offensive_comments WHERE comments_id IN (?)", (array)$comments_id);
		DBManager::Get('devices')->query("UPDATE comments SET offensive_counts = 0 WHERE comments_id IN (?)", (array)$comments_id);
		DBManager::Get('devices')->query("DELETE FROM offensive_comments WHERE comments_id IN (?)", (array)$comments_id);
	}

	public function Complete_Delete($comments_id)
	{
		if(isset($_REQUEST['comments_id']))
		{
			DBManager::Get('devices')->query("DELETE FROM offensive_comments WHERE comments_id = ?", $_REQUEST['comments_id']);
			FrontController::Relocate($_REQUEST['return_to']);
		}

		DBManager::Get('devices')->query("DELETE FROM offensive_comments WHERE comments_id IN (?)", (array)$comments_id);
	}

	public function Admin()
	{
		$template = $this->get_template(true);
		$limit = 10;
		$limit_notify = 10;
		$limit_delete = 10;
		$skip = 0;
		$skip_notify = 0;
		$skip_delete = 0;

		$comments = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT device_id, comments_id, comment, language, category, youser_id, positive, negative, UNIX_TIMESTAMP(timestamp) as timestamp, offensive_counts FROM comments WHERE offensive_counts < 2 ORDER BY timestamp DESC")->to_array();
		$offensive_comments = DBManager::Get('devices')->skip($skip_notify)->limit($limit_notify)->query("SELECT device_id, comments_id, comment, language, category, youser_id, positive, negative, UNIX_TIMESTAMP(timestamp) as timestamp, offensive_counts FROM comments WHERE offensive_counts >= 2 ORDER BY timestamp DESC")->to_array();
		$deleted_comments = DBManager::Get('devices')->skip($skip_delete)->limit($limit_delete)->query("SELECT device_id, comments_id, comment, language, category, youser_id, positive, negative, UNIX_TIMESTAMP(timestamp) as timestamp, offensive_counts FROM offensive_comments ORDER BY timestamp DESC")->to_array();
		$template->assign('burn', 2);
		$template->assign('offensive_comments', $offensive_comments);
		$template->assign('comments', $comments);
		$template->assign('deleted', $deleted_comments);
	}

	public function Mass_Action()
	{
		if(isset($_REQUEST['comments_delete']))
		{
			self::Delete($_REQUEST['comments_delete']);
		}
		if(isset($_REQUEST['comments_undelete']))
		{
			self::Undelete($_REQUEST['comments_undelete']);
		}
		if(isset($_REQUEST['comments_notify']))
		{
			self::Burn($_REQUEST['comments_notify']);
		}
		if(isset($_REQUEST['comments_unnotify']))
		{
			self::Unburn($_REQUEST['comments_unnotify']);
		}
		if(isset($_REQUEST['comments_delete_final']))
		{
			self::Complete_Delete($_REQUEST['comments_delete_final']);
		}
		FrontController::Relocate('Admin');
	}
	
	public function Translate(){
		$comments_id = $_REQUEST['comments_id'];
		$comment = DBManager::Get('devices')->query("SELECT comment FROM comments WHERE comments_id = ?;", $comments_id)->fetch_item();
		$comment = GoogleTranslate::Get()->Translate(strip_tags($comment));
		$comment = comment_handler::find_links($comment);
		$template = $this->get_template(true);
		$template->assign('comment', $comment);
	}
}
?>
