<?php
class Beta_Logs extends Controller
{
	public function Index()
	{
		$template = $this->get_template(true);
		$template->assign('yousers', DBManager::Query("SELECT DISTINCT y.youser_id, UNIX_TIMESTAMP(lastaction) AS lastaction FROM yousers y JOIN youser_permission yp ON (y.youser_id=yp.youser_id AND yp.role='betatester')")->to_array('youser_id', 'lastaction'));
	}

	public function Logs()
	{
		$display_max = 25;
		$page = isset($_REQUEST['page'])
			? $_REQUEST['page']
			: 0;

		$data = DBManager::Get()->skip($page*$display_max)->limit($display_max)->query("SELECT session_id, UNIX_TIMESTAMP(timestamp) AS timestamp, location, get_parameters, post_parameters, user_agent, language FROM beta_log WHERE youser_id=? ORDER BY timestamp ASC", $_REQUEST['youser_id'])->to_array();

		$template = $this->get_template(true);
		$template->assign('display_max', $display_max);
		$template->assign('page', $page);
		$template->assign('data', $data);
		$template->assign('total', DBManager::Get()->query("SELECT COUNT(*) FROM beta_log WHERE youser_id=?", $_REQUEST['youser_id'])->fetch_item());
	}

	public function Download()
	{
		Header('Content-Type: text/comma-separated-values');
		Header('Content-Disposition: attachment; filename="youser_log_'.$_REQUEST['youser_id'].'_'.Youser::Get($_REQUEST['youser_id'])->nickname.'_'.time().'.csv"');
		print '"Session-Id","Timestamp","Location","GET-Parameter","POST-Parameter","User-Agent","Sprache"'."\n";
		$data = DBManager::Get()->query("SELECT session_id, timestamp, location, get_parameters, post_parameters, user_agent, language FROM beta_log WHERE youser_id=? ORDER BY timestamp ASC", $_REQUEST['youser_id'])->to_array();
		foreach ($data as $row)
		{
			printf('"%s","%s","%s","%s","%s","%s","%s"'."\n",
				$row['session_id'],
				$row['timestamp'],
				$row['location'],
				str_replace('"', '""', $row['get_parameters']),
				str_replace('"', '""', $row['post_parameters']),
				$row['user_agent'],
				$row['language']
			);
		}
		die;
	}
}
?>