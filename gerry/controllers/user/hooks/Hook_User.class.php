<?php
class Hook_User extends Hook
{
	public static $hooks = array(
		'Config:Options'=>'GetOptions',
		'System:Wakeup'=>'Wakeup',
		'Navigation:Plugin'=>'OnlineList',
		'Confirmed:activation'=>'ActivationConfirm',
		'Confirmed:emailchange'=>'EmailChangeConfirm',
		'Confirmed:passwordreminder'=>'PasswordReminderConfirm',
		'User:Poll'=>'Poll',
		'Controller:Got'=>'PermissionCheck'
	);

	public static function GetOptions()
	{
		return array(
			'useradministration:pagination_count:range:5,30,5',
			'system:password_length:range:8,32,1',
			'system:online_duration:range:1,10,1',
			'connection:pagination_count:range:5,30,5',
			'pinboard:pagination_count:range:5,30,5',
			'profile:layout:text',
			'profile:display_layout:text',
		);
	}

	public static function PermissionCheck($location)
	{
		if (!Permissions::CheckPermission( Youser::GetRole(), implode('/', $location)))
		{
			FirePHP::getInstance(true)->log($location, 'Location');
			Dobber::ReportError('ERROR_INVALID_RIGHTS');
			$location = array(
				'module' => 'System',
				'controller' => 'System',
				'method' => 'Error'
			);
		}
		return $location;
	}

	public static function Poll($youser_id)
	{
		return array(
			'messages'=>numberformat(Message::GetUnseenMessages($youser_id)),
			'activities'=>numberformat(Activity::GetNewSince($youser_id, Session::Get('activity', 'timestamp'))),
			'credits'=>numberformat(YouserCredits::GetCredits(), 0),
			'transactions'=>numberformat(YouserCredits::GetUnseenCount(), 0),
			'youseronline'=>numberformat(count(Youser::GetUsersOnline())),
		);
	}

	public static function Wakeup()
	{
		if (Session::Get('Youser', 'id')!==null)
		{
			Youser::Beat();
			return;
		}
	}

	public static function OnlineList($action)
	{
		if ($action!=__CLASS__.':'.__FUNCTION__)
		{
			return false;
		}
		$template = self::get_template('hook_user_onlinelist');
		$template->assign('users_online', Youser::GetUsersOnline());
		return $template->render();
	}

	public static function ActivationConfirm($result, $youser_id, $subject)
	{
		if (!$result)
		{
			Dobber::ReportError('ERROR_PERSONAL_ACCOUNT_ACTIVATION');
			return false;
		}
		Dobber::ReportSuccess('SUCCESS_PERSONAL_ACCOUNT_ACTIVATED');
		FrontController::Relocate('User', 'Access', 'Login');
	}

	public static function EmailChangeConfirm($result, $youser_id, $subject)
	{
		if (!$result)
		{
			Dobber::ReportError('ERROR_EMAIL_CHANGE_CONFIRM');
			return false;
		}
		elseif (Youser::EmailExists($subject))
		{
			Dobber::ReportError('ERROR_EMAIL_CHANGE_EMAIL_EXISTS');
			return false;
		}
		$youser = Youser::Get($youser_id);
		$youser->email = $subject;
		if (!$youser->save())
		{
			Dobber::ReportError('ERROR_EMAIL_CHANGE');
			return false;
		}
		return 'SUCCESS_EMAIL_CHANGE';
	}

	public static function PasswordReminderConfirm($result, $youser_id, $subject)
	{
		if (!$result)
		{
			Dobber::ReportError('ERROR_REQUEST_NEW_PASSWORD');
			return false;
		}

		$haystack = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$new_password = '';
		for ($i=0; $i<Config::Get('system:password_length'); $i++)
		{
			$char = substr($haystack, mt_rand(0, strlen($haystack)-1), 1);
			if (mt_rand(0,1))
			{
				$char = strtoupper($char);
			}
			$new_password .= $char;
		}

		$youser = Youser::Get($youser_id);
		$youser->setPassword($new_password);

		if (!$youser->save())
		{
			Dobber::ReportError('ERROR_REQUEST_NEW_PASSWORD_SAVE');
			return false;
		}

		Mailer::SendTextMail($youser->email, Config::Get('mail:youserbase_from'), 'Neues Passwort', 'passwordreminder_new', array(
			'nickname'=>$youser->nickname,
			'password'=>$new_password
		));
		return 'SUCCESS_REQUEST_PASSWORD';
	}
}
?>