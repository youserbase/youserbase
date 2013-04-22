<?php
class Administration_Comment extends Controller
{
	public function Index(){
		$template = $this->get_template(true);
		$comments = DBManager::Get('devices')->query("SELECT * FROM comments WHERE granted = 0;")->to_array();
		$template->assign('comments', $comments);
	}
	
	public function Delete(){
		if(isset($_REQUEST['comments_id']) && is_numeric($_REQUEST['comments_id'])){
			$comments_id = $_REQUEST['comments_id'];
		}else{
			FrontController::Relocate('Index');
		}
		DBManager::Get('devices')->query('INSERT INTO offensive_comments SELECT * FROM comments WHERE comments.comments_id = ?;', $comments_id);
		DBManager::Get('devices')->query("DELETE FROM comments WHERE comments_id = ?;", $comments_id);
		FrontController::Relocate('Index');
	}
	
	public function Publish(){
		if(isset($_REQUEST['comments_id']) && is_numeric($_REQUEST['comments_id'])){
			$comments_id = $_REQUEST['comments_id'];
		}else{
			FrontController::Relocate('Index');
		}
		$youser_id = 107;
		if(Youser::Id()){
			$youser_id = Youser::Id();
		}
		if($_REQUEST['compare'] == 'true'){
			Event::Dispatch('alert', 'Youser:EditCompareComment', $youser_id, $_REQUEST['device_id']);
		}else{ 
			Event::Dispatch('alert', 'Youser:WroteComment', $youser_id, $_REQUEST['device_id']);
		}
		DBManager::Get('devices')->query("UPDATE comments SET granted = 1 WHERE comments_id = ?;", $comments_id);
		FrontController::Relocate('Index');
	}
}
?>