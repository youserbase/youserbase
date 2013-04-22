<?php
class Hook_User_Mail extends Hook
{
	public static $hooks = array(
		'Navigation:Plugin'=>'YouserMailCheck',
		'Config:Options'=>'GetOptions'
	);

	public static function GetOptions()
	{
		return array(
			'mail:hideifempty:bool',
			'mail:pagination_count:range:5,30,5',
			'mail:send_copy_via_email:bool',
			'mail:youserbase_from:string:required'
		);
	}

	public static function YouserMailCheck($action)
	{
		if ($action!=__CLASS__.':'.__FUNCTION__)
		{
			return false;
		}
		$mail_count = Message::GetUnseenMessages(Youser::Id());
		if (Config::Get('mail', 'hideifempty') and $mail_count==0)
		{
			return false;
		}
		$template = self::get_template('hook_user_mailcheck');
		$template->assign('mail_count', $mail_count);
		return $template->render();
	}
}
?>