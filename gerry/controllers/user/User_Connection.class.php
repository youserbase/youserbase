<?php
class User_Connection extends Controller
{
	public function Add()
	{
		if ($this->Posted('youser_id'))
		{
			if ($this->Posted('cancel'))
			{
				FrontController::Relocate('Profile', 'Nickpage', array('youser'=>Youser::Get($_REQUEST['youser_id'])->nickname));
			}

			Connection::Connect(Youser::Id(), $_POST['youser_id']);

			$other_user = Youser::Get($_POST['youser_id']);
			$confirmation_key = Confirmation::Request('connection', Youser::Id(), $other_user->id, false);
			Mailer::SendTextMail($other_user->email, Config::Get('mail:youserbase_from'), /* PHRASE */Youser::Get()->nickname.' möchte Sie als Freund hinzufügen', 'connection', array(
				'nickname'=>$other_user->nickname,
				'youser_nickname'=>Youser::Get()->nickname,
				'link'=>FrontController::GetAbsoluteURI().FrontController::GetLink('System', 'System', 'Confirm', array('key'=>$confirmation_key, 'preferred_language'=>false)),
				'youser_link'=>FrontController::GetAbsoluteURI().FrontController::GetLink('User', 'Nickpage', 'Display', array('youser'=>Youser::Get()->nickname))
			));
			Dobber::ReportNotice('SUCCESS_CONNECTION_REQUEST_SENT', array('nickname'=>$other_user->nickname));
			Lightbox::Close();
		}
		elseif (!$this->Getted('youser_id'))
		{
			Dobber::ReportError('INVALID_REQUEST');
			FrontController::Relocate('System', 'System', 'Error');
		}
		$template = $this->get_template(true);
		$template->assign('youser_id', $_REQUEST['youser_id']);
		$template->assign('nickname', Youser::Get($_REQUEST['youser_id'])->nickname);
	}

	public function Remove()
	{
		if ($this->Posted('youser_id'))
		{
			if ($this->Posted('cancel'))
			{
				FrontController::Relocate('Profile', 'Nickpage', array('youser'=>Youser::Get($_REQUEST['youser_id'])->nickname));
			}
			Connection::Remove(Youser::Id(), $_POST['youser_id']);
			Dobber::ReportNotice('SUCCESS_CONNECTION_ENDED', array('nickname'=>Youser::Get($_REQUEST['youser_id'])->nickname));
			Lightbox::Close();
		}
		elseif (!$this->Getted('youser_id'))
		{
			Dobber::ReportError('INVALID_REQUEST');
			FrontController::Relocate('System', 'System', 'Error');
		}
		$template = $this->get_template(true);
		$template->assign('youser_id', $_REQUEST['youser_id']);
		$template->assign('nickname', Youser::Get($_REQUEST['youser_id'])->nickname);
	}

	public function Display()
	{
		$youser = isset($_REQUEST['youser_id'])
			? Youser::Get($_REQUEST['youser_id'])
			: Youser::Get();

		$template = $this->get_template(true);
		$template->assign('friend_count', Connection::GetCount($youser->id));
		$template->assign('youser', $youser);
	}

	public function Display_All()
	{
		$youser = isset($_REQUEST['youser_id'])
			? Youser::Get($_REQUEST['youser_id'])
			: Youser::Get();
		$current_page = (isset($_REQUEST['page']) and preg_match('/^\d+$/S', $_REQUEST['page']))
			? $_REQUEST['page']
			: 0;

		$yousers = array_map(create_function('$a', 'return $a->to_array();'), Connection::Get($youser->id, $current_page*Config::Get('connection:pagination_count'), Config::Get('connection:pagination_count')));

		$template = $this->get_template(true);
		$template->assign('yousers', $yousers);
		$template->assign('friend_count', Connection::GetCount($youser->id));
		$template->assign('current_page', $current_page);
	}

	public function DisplayRequests()
	{
		$template = $this->get_template(true);
	}
}
?>