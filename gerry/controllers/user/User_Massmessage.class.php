<?php
class User_Massmessage extends Controller 
{
	public function Index()
	{
		$template = $this->get_template(true);
		if(isset($_REQUEST['ids']))
		{
			$template->assign('ids', $_REQUEST['ids']);
		}
		$yousers = DBManager::Get()->query("SELECT youser_id FROM yousers ORDER BY youser_id;")->to_array();
		$template->assign('yousers', $yousers);
	}
	
	public function Send()
	{
		if(isset($_REQUEST['youser_id']))
		{
			$youser_ids = $_REQUEST['youser_id'];
			if(isset($_REQUEST['mail_text']))
			{
				$text = $_REQUEST['mail_text'];
			}
			foreach ($youser_ids as $youser_id)
			{
				$nickname = DBManager::Get()->query("SELECT nickname FROM yousers WHERE youser_id = ?", $youser_id)->fetch_item();
				$send_text = str_replace('[yousername]', $nickname, $text);
				$params = array(
					'nickname'=>$nickname,
					'sender'=>(DBManager::Get()->query("SELECT nickname FROM yousers WHERE youser_id = ?", 152)->fetch_item())
				);
				if (Config::Get('mail:send_copy_via_email'))
				{
					$params['message'] = $send_text;
					$params['subject'] = $_POST['subject'];
					Mailer::SendTextMail(Youser::Get($youser_id)->email, Config::Get('mail:youserbase_from'), /* PHRASE */'Neue Nachricht von '.$params['sender'], 'mail_received', $params, Youser::Get($youser_id)->language);
				}
			
				$message = new Message();
				$message->sender_id = 152;
				$message->receiver_id = $youser_id;
				$message->subject = $_POST['subject'];
				$message->message = $send_text;
				$message->reply_to = 'support@youserbase.org';
				$message->save();
			}
		}
		FrontController::Relocate('Index', array('ids' => $youser_ids));
	}
	
	
}
?>