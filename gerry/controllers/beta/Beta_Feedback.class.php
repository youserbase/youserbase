<?php
class Beta_Feedback extends Controller
{
	public function Submit()
	{
		DBManager::Query("INSERT INTO beta_feedback (youser_id, location, timestamp, feedback, user_agent, session_id) VALUES (?, ?, NOW(), TRIM(?), ?, ?)",
			Youser::Id(),
			$_POST['location'],
			$_POST['feedback'],
			$_SERVER['HTTP_USER_AGENT'],
			Session::Id()
		);
		$this->get_template(false, '<phrase id="BETA_FEEDBACK_SENT"/>');
	}

	public function Display()
	{
		$this->get_template(true);
	}

	public function Index()
	{
		$limit = 30;
		$skip = isset($_REQUEST['page']) ? $_REQUEST['page']*$limit : 0;

		$template = $this->get_template(true);
		$template->assign('display_limit', $limit);
		$template->assign('display_skip', $skip);
		$template->assign('total', DBManager::Query("SELECT COUNT(*) FROM beta_feedback")->fetch_item());
		$template->assign('feedback', DBManager::Get()->skip($skip)->limit($limit)->query("SELECT youser_id, UNIX_TIMESTAMP(timestamp) AS timestamp, feedback_id, feedback, user_agent, session_id, resolved_by, UNIX_TIMESTAMP(resolved_timestamp) AS resolved_timestamp FROM beta_feedback ORDER BY timestamp DESC")->to_array());
	}

	public function Read()
	{
		$this->get_template(true)
			->assign(DBManager::Query("SELECT feedback, location, user_agent FROM beta_feedback WHERE feedback_id=?", $_REQUEST['feedback_id'])->fetch_array());
	}

	public function Toggle()
	{
		DBManager::Query("UPDATE beta_feedback SET resolved_by=IF(resolved_by IS NULL, ?, NULL), resolved_timestamp=IF(resolved_timestamp IS NULL, NOW(), NULL) WHERE feedback_id=?",
			Youser::Id(),
			$_REQUEST['feedback_id']
		);
		FrontController::Relocate('Index', array('page'=>isset($_REQUEST['page'])?$_REQUEST['page']:0));
	}

	public function Delete()
	{
		DBManager::Query("DELETE FROM beta_feedback WHERE feedback_id=?", $_GET['feedback_id']);
		FrontController::Relocate('Index', array('page'=>isset($_REQUEST['page'])?$_REQUEST['page']:0));
	}
}
