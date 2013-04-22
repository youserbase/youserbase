<?php
class User_InviteUsers extends Controller
{
	public function Index()
	{
		$template = $this->get_template(true);
		if(isset($_GET['mail']))
		{
			$template->assign('mail', $_GET['mail']);
		}
		if(isset($_GET['invalid']))
		{
			$template->assign('invalid', $_GET['invalid']);
		}
		$mail_text = Mail::get_mail_text('invitation');
		if(Youser::Id())
		{
			$youser = Youser::Get()->nickname;
		}
		$mail_text = str_replace('(name)', $youser, $mail_text);
		$template->assign('youser', $youser);
		$template->assign('mail_text', $mail_text);
	}

	public function Invite()
	{
		if(YouserInvitation::Invite() === true)
		{
			Dobber::ReportNotice('SUCCESS_FRIEND_INVITED');
		}
		FrontController::DirectRelocate(FrontController::GetAbsoluteURI());
	}
}
?>