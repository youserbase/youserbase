<?php
class Hook_UserConnection extends Hook
{
	public static $hooks = array(
		'Confirmed:connection'=>'ConnectionConfirmed',
		'Declined:connection'=>'ConnectionDeclined',
		'Plugin:FriendRequest'=>'FriendRequests'
	);

	public static function ConnectionConfirmed($result, $youser_id, $subject)
	{
		if (!$result)
		{
			Dobber::ReportError('ERROR_CONNECTION_CONFIRM');
			return false;
		}
		Connection::Confirm($youser_id, $subject);
		Dobber::ReportSuccess('SUCCESS_CONNECTION_CONFIRM');
		Event::Dispatch('alert', 'Youser:FriendAdd', $youser_id, $subject);
		FrontController::Relocate('User', 'Nickpage', 'Display', array('youser'=>Youser::Get($youser_id)->nickname));
	}

	public static function ConnectionDeclined($youser_id, $subject)
	{
		Connection::Remove($youser_id, $subject);
	}

	public static function FriendRequests($youser_id)
	{
		$open_requests = Connection::FindOpenRequests($youser_id);
		if (empty($open_requests))
		{
			return false;
		}

		$template = self::get_template('connection_friendrequests');
		$template->assign('open_requests', $open_requests);
		$template->assign('confirmation_data', Confirmation::GetBySubject('connection', $youser_id));
		return $template->fetch();
	}
}