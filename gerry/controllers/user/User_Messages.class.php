<?php
class User_Messages extends Controller
{
	public function Index()
	{
		$template = $this->get_template(false);
/*
		$template->assign('inbound_count', Message::GetInboundCount(Youser::Id()));
		$template->assign('outbound_count', Message::GetOutboundCount(Youser::Id()));
*/
	}

	public function Inbox()
	{
		$current_page = (isset($_REQUEST['page']) and preg_match('/^\d+$/S', $_REQUEST['page']))
			? $_REQUEST['page']
			: 0;

		$template = $this->get_template(true);
		$template->assign('inbound_count', Message::GetInboundCount(Youser::Id()));
		$template->assign('current_page', $current_page);
		$template->assign('messages', Message::GetInbound(Youser::Id(), $current_page*Config::Get('mail', 'pagination_count'), Config::Get('mail', 'pagination_count')));
	}

	public function Inbox_Actions()
	{
		$message_ids = isset($_POST['all_messages'])
			? explode(',', $_POST['all_messages'])
			: $_POST['messages'];

		if (isset($_POST['read']))
		{
			Message::ToggleRead($message_ids, Youser::Id());
		}
		elseif (isset($_POST['unread']))
		{
			Message::ToggleUnread($message_ids, Youser::Id());
		}
		elseif (isset($_POST['delete']))
		{
			Message::DeleteInbound(Youser::Id(), $message_ids);
		}
		FrontController::Relocate('Index', array('#'=>'Inbox'));
	}

	public function Inbox_Actions_AJAX()
	{
		$changed = false;
		if (isset($_POST['read']))
		{
			$changed = Message::ToggleRead($_POST['messages'], Youser::Id());
			if ($changed!==false)
			{
				Dobber::ReportSuccess('SUCCESS_MESSAGES_MARKED_READ');
			}
		}
		elseif (isset($_POST['unread']))
		{
			$changed = Message::ToggleUnread($_POST['messages'], Youser::Id());
			if ($changed!==false)
			{
				Dobber::ReportSuccess('SUCCESS_MESSAGES_MARKED_UNREAD');
			}
		}
		$template = $this->get_template(false, json_encode(array('success'=>$changed!==false)));
	}

	public function Outbox()
	{
		$current_page = (isset($_REQUEST['page']) and preg_match('/^\d+$/S', $_REQUEST['page']))
			? $_REQUEST['page']
			: 0;

		$template = $this->get_template(true);
		$template->assign('outbound_count', Message::GetOutboundCount(Youser::Id()));
		$template->assign('current_page', $current_page);
		$template->assign('messages', Message::GetOutbound(Youser::Id(), $current_page*Config::Get('mail', 'pagination_count'), Config::Get('mail', 'pagination_count')));
	}

	public function Outbox_Actions()
	{
		$message_ids = isset($_POST['all_messages'])
			? explode(',', $_POST['all_messages'])
			: $_POST['messages'];

		if (isset($_POST['delete']))
		{
			Message::DeleteOutbound(Youser::Id(), $message_ids);
		}
		FrontController::Relocate('Index', array('#'=>'Outbox'));
	}

	public function Send_POST()
	{
		$message = new Message();
		$message->sender_id = Youser::Id();
		$message->receiver_id = $_POST['to'];
		$message->subject = $_POST['subject'];
		$message->message = $_POST['message'];
		if (isset($_REQUEST['reply_to']))
		{
			$message->reply_to = $_REQUEST['reply_to'];
		}
		$message->save();

		$params = array(
			'nickname'=>Youser::Get($_POST['to'])->nickname,
			'sender'=>Youser::Get()->nickname
		);
		if (Config::Get('mail:send_copy_via_email'))
		{
			$params['message'] = $_POST['message'];
			$params['subject'] = $_POST['subject'];
		}
		Mailer::SendTextMail(Youser::Get($_POST['to'])->email, Config::Get('mail:youserbase_from'), /* PHRASE */'Neue Nachricht von '.$params['sender'], 'mail_received', $params, Youser::Get($_POST['to'])->language);

		Dobber::ReportSuccess('SUCCESS_MESSAGE_SENT');
		if ($this->via_ajax)
		{
			Lightbox::Close();
			$this->get_template(false, '');
			return;
		}
		else
		{
			FrontController::Relocate('Index');
		}
	}

	public function Send()
	{
		if (empty($_REQUEST['subject']) and !empty($_REQUEST['reply_to']))
		{
			$message = new Message($_REQUEST['reply_to'], Youser::Id());
			if (preg_match('/^Re(?:\^(?P<count>\d+))?: /', $message->subject, $match))
			{
				$count = empty($match['count'])?'2':$match['count']+1;
				$prefix = 'Re^'.$count.': ';
				$_REQUEST['subject'] = preg_replace('/^'.preg_quote($match[0]).'/', $prefix, $message->subject);
			}
			else
			{
				$_REQUEST['subject'] = 'Re: '.$message->subject;
			}
		}
		$template = $this->get_template(true);
		$template->assign('nickname', Youser::Get($_REQUEST['to'])->nickname);
		$template->assign('to', $_REQUEST['to']);
		$template->assign('reply_to', isset($_REQUEST['reply_to'])?$_REQUEST['reply_to']:false);
		$template->assign('subject', isset($_REQUEST['subject'])?trim($_REQUEST['subject']):'');
		$template->assign('message', isset($_REQUEST['message'])?trim($_REQUEST['message']):'');
	}

	public function Delete()
	{
		if ($_GET['type']=='outbound')
		{
			Message::DeleteOutbound(Youser::Id(), $_GET['message_id']);
			FrontController::Relocate('Index', array('#'=>'Outbox'));
		}
		elseif ($_GET['type']=='inbound')
		{
			Message::DeleteInbound(Youser::Id(), $_GET['message_id']);
			FrontController::Relocate('Index', array('#'=>'Inbox'));
		}
	}

	public function Read()
	{
		$template = $this->get_template(true);
		$template->assign( Message::Read($_GET['message_id'], Youser::Id()) );
	}
}
?>