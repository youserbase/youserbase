<?php
class User_Access extends Controller
{
	public function Login()
	{
		if ($this->Posted() and isset($_POST['nickname']))
		{
			$all_set = array_reduce(array('nickname', 'password'),
				create_function('$v, $w', 'return ($v and !empty($_POST[$w]));'), true);
			if (!$all_set)
			{
				Dobber::ReportError('ERROR_FORM_INCOMPLETE_DATA');
			}
			elseif (!$youser_id = Youser::Validate($_POST['nickname'], $_POST['password']))
			{
				Dobber::ReportError('INVALID_NICKNAME_PASSWORD_COMBINATION');
			}
			elseif (!Confirmation::Confirmed('activation', $youser_id))
			{
				Dobber::ReportError('ERROR_PERSONAL_ACCOUNT_NOT_ACTIVATED');
			}

			if (Dobber::IsEmpty('error'))
			{
				Youser::Login($youser_id, empty($_POST['store_login']) ? 0 : 365 * 24 * 60 * 60);

				Dobber::ReportSuccess('SUCCESS_LOGGED_IN');

				if (isset($_REQUEST['return_to']))
				{
					FrontController::DirectRelocate($_REQUEST['return_to']);
				}
				else
				{
					FrontController::Relocate('User', 'Profile', 'Index');
				}
			}
		}

		$template = $this->get_template(true);
		if (isset($_REQUEST['return_to']))
		{
			$template->assign('return_to', $_REQUEST['return_to']);
		}
	}

	public function Logout()
	{
		if (Session::Get('Youser', 'old_id'))
		{
			Session::Set('Youser', 'id', Session::Get('Youser', 'old_id'));
			Session::Clear('Youser', 'old_id');
		}
		else
		{
			Event::Dispatch('alert', 'Youser:Logout', Session::Get('Youser', 'id'));
			Youser::Logout();
			Dobber::ReportSuccess('SUCCESS_LOGGED_OUT');
		}

		if (isset($_REQUEST['return_to']))
		{
			FrontController::DirectRelocate($_REQUEST['return_to']);
		}
		else
		{
			FrontController::Relocate(false);
		}
	}

	public function Register()
	{
		if (!empty($_POST))
		{
			// TODO: Überprüfung auf doppelten Nickname, eMail
			$all_set = array_reduce(array('nickname', 'password', 'password_confirm', 'email'),
				create_function('$v, $w', 'return !empty($_POST[$w]) ? $v : false;'), true);
			if (!$all_set)
			{
				Dobber::ReportError('Es wurden nicht alle Felder ausgefüllt');
			}
			else
			{
				if ($_POST['password']!=$_POST['password_confirm'])
				{
					Dobber::ReportError('ERROR_FORM_PASSWORD_NOT_CONFIRMED');
				}
				if (Youser::NicknameExists($_POST['nickname']))
				{
					Dobber::ReportError('ERROR_FORM_NICKNAME_TAKEN');
				}
				if (Youser::EmailExists($_POST['email']))
				{
					Dobber::ReportError('ERROR_FORM_EMAIL_TAKEN');
				}
			}


			if (Dobber::IsEmpty('error'))
			{

				$youser = new Youser();
				$youser->nickname = trim($_POST['nickname']);
				$youser->email = trim($_POST['email']);
				$youser->setPassword(trim($_POST['password']));
				$youser->language = BabelFish::GetLanguage();

				if ($youser->save())
				{
					Event::Dispatch('alert', 'Youser:Register', $youser->id);

					$this->SendActivationLink($youser);

					Dobber::ReportSuccess('SUCCESS_YOUSER_REGISTERED');
					if($youser->language == 'de')
					{
						$text = "Hi [yousername],

schön dich als neuen youser begrüßen zu dürfen.

Ich hoffe die Seite gefällt dir, du findest was du suchst und hast Spaß.

Du darfst alles editieren und neue Geräte anlegen, wenn dir welche fehlen sollten.

Wir sind noch ganz am Anfang und in den nächsten Wochen werden einige neue Funktionen eingebaut werden. Wir würden uns freuen, wenn du jetzt häufiger mal reinschauen würdest.

Wenn du Verbesserungsvorschläge, Lob oder Kritik hast: immer her damit.

Wenn du auf einen Avatar oder yousernamen klickst kommst du auf die Nickpage, dort kannst du dann auf die Pinwand schreiben oder eine Nachricht abschicken.

Gruß

maunsen

youserbase";
						$text = str_replace('[yousername]', $youser->nickname, $text);
						Pinboard::Add($youser->id, 3, $text);
					}
					FrontController::Relocate(array('Registered'=>null));
				}
			}
		}

		$template = $this->get_template(true, isset($_REQUEST['Registered']) ? 'registered' : null);
	}

	private function SendActivationLink(Youser $youser)
	{
		$confirmation_key = Confirmation::Request('activation', $youser->id);

		Mailer::SendTextMail($youser->email, Config::Get('mail:youserbase_from'), BabelFish::Get('MAIL_REGISTRATION_SUBJECT'), 'activation', array(
			'nickname'=>$youser->nickname,
			'email'=>$youser->email,
			'link'=>FrontController::GetAbsoluteURI().FrontController::GetLink('System', 'System', 'Confirm', array('key'=>$confirmation_key, 'preferred_language'=>false))
		));
	}

	public function Activate()
	{
		if (!$this->Getted('youser_id', 'key'))
		{
			Dobber::ReportError('INVALID_REQUEST');
		}
		if (Confirmation::Confirmed('activation', $_REQUEST['youser_id'], $_REQUEST['key'])===false)
		{
			Dobber::ReportError('ERROR_PERSONAL_ACCOUNT_ACTIVATION');
		}
		if (!Dobber::IsEmpty('error'))
		{
			FrontController::Relocate('System', 'System', 'Error');
		}
		if(isset($_REQUEST['youser_id']))
		{
			$mail = Youser::Get($_REQUEST['youser_id'])->email;
			$this->CheckInvitation($mail);
		}
		$template = $this->get_template(true);
	}

	public function ResendActivation()
	{
		if ($this->Posted())
		{
			$youser = Youser::GetByEmail($_POST['email']);
			if ($youser===false)
			{
				Dobber::ReportError('ERROR_UNKNOWN_YOUSER_EMAIL');
			}
			elseif (Confirmation::Confirmed('activation', $youser->id))
			{
				Dobber::ReportError('ALREADY_ACTIVATED');
			}
			if (Dobber::IsEmpty('error'))
			{
				if ($this->SendActivationLink($youser) !== false)
				{
					//ToDo Coins an youser verteilen
				}
				FrontController::Relocate(array('Sent'=>null));
			}
		}

		$template = $this->get_template(true, isset($_REQUEST['Sent']) ? 'sent' : null);
	}

	public function PasswordReminder()
	{
		if ($this->Posted())
		{
			$youser = Youser::GetByEmail($_POST['email']);
			if ($youser===false)
			{
				Dobber::ReportError('ERROR_UNKNOWN_YOUSER_EMAIL');
			}
			if (Dobber::IsEmpty('error'))
			{
				$confirmation_key = Confirmation::TimedRequest('passwordreminder', $youser->id, 24*60*60);

				Mailer::SendTextMail($youser->email, Config::Get('mail:youserbase_from'), /* PHRASE */'Passwort vergessen?', 'passwordreminder', array(
					'nickname'=>$youser->nickname,
					'link'=>FrontController::GetAbsoluteURI().FrontController::GetLink('System', 'System', 'Confirm', array('key'=>$confirmation_key, 'preferred_language'=>false))
				));
				FrontController::Relocate(array('Sent'=>null));
			}
		}
		$template = $this->get_template(true, isset($_REQUEST['Sent']) ? 'sent' : null);
	}

	private function CheckInvitation($mail)
	{
		$youser_id = DBManager::Get('devices')->query("SELECT youser_id FROM invitations WHERE invited_mail = ?;",md5(trim($mail)))->fetch_item();
		if(!$youser_id)
		{
			return false;
		}
		DBManager::Get('devices')->query("DELETE FROM invitations WHERE invited_mail = ?;", md5(trim($mail)));
		return $youser_id;
	}
}
?>