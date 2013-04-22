<?php
class User_Administration extends Controller
{
	public function Index()
	{
		$current_page = (isset($_REQUEST['page']) and preg_match('/^\d+$/S', $_REQUEST['page']))
			? $_REQUEST['page']
			: 0;

		$template = $this->get_template(true);
		$template->assign('roles', Youser::GetRoles());
		$template->assign('yousers', Youser::GetBundle($current_page*Config::Get('useradministration:pagination_count'), Config::Get('useradministration:pagination_count')));
		$template->assign('youser_count', Youser::GetYouserCount());
		$template->assign('current_page', $current_page);
	}

	public function Edit_POST()
	{
		$youser = Youser::Get($_REQUEST['youser_id']);

		if (!$this->Posted('nickname', 'email'))
		{
			Dobber::ReportError('ERROR_FORM_INCOMPLETE_DATA');
		}
		else
		{
			if ($_POST['nickname']!=$youser->nickname and Youser::NicknameExists($_POST['nickname']))
			{
				Dobber::ReportError('ERROR_FORM_NICKNAME_TAKEN');
			}
			if ($_POST['email']!=$youser->email and Youser::EmailExists($_POST['email']))
			{
				Dobber::ReportError('ERROR_FORM_EMAIL_TAKEN');
			}
			if (!in_array($_POST['role'], Navigation::GetRoles()))
			{
				Dobber::ReportError('ERROR_INVALID_ROLE');
			}
		}

		if (Dobber::IsEmpty('error'))
		{
			if (isset($_POST['roles']))
			{
				YouserPermissions::Get($youser->id)->clear_roles()->add_role($_POST['roles'])->load_permissions();
			}

			$youser->nickname = $_POST['nickname'];
			$youser->email = $_POST['email'];
			$youser->role = $_POST['role'];
			$youser->blocked = (!empty($_POST['blocked']) and $_POST['blocked']=='yes') ? 'yes' : 'no';
			$youser->visible = (!empty($_POST['visible']) and $_POST['visible']=='yes') ? 'yes' : 'no';
			if ($youser->save())
			{
				Event::Dispatch('alert', 'Youser:ChangeDetails', $youser->id);
				Dobber::ReportSuccess('Die Daten wurden erfolgreich aktualisiert');
				if ($this->via_ajax)
				{
					Lightbox::Close();
					$this->get_template(false, '');
					return;
				}
				FrontController::Relocate(array('youser_id'=>$youser->id));
			}
			else
			{
				Dobber::ReportError('Es gab einen Fehler beim Speichern der Daten');
			}
		}
		return false;
	}

	public function Edit()
	{
		if (empty($_REQUEST['youser_id']))
		{
			throw new Exception('No youser id specified');
		}

		$template = $this->get_template(true);
		$template->assign('youser', Youser::Get($_REQUEST['youser_id']));
		$template->assign('roles', Youser::GetRoles());
	}

	public function Insert_POST()
	{
		$youser = new Youser();
		$youser->nickname = $_POST['nickname'];
		$youser->email = $_POST['email'];
		$youser->setPassword($_POST['password']);
		$youser->role = $_POST['role'];
		$youser->language = BabelFish::GetLanguage();
		$youser->save();

		Dobber::ReportSuccess('Der Benutzer wurde erfolgreich angelegt');

		FrontController::Relocate('Index');
	}

	public function Insert()
	{
		$template = $this->get_template(true);
		$template->assign('roles', Youser::GetRoles());
	}

	public function Delete()
	{
		Youser::Delete($_GET['youser_id']);

		Dobber::ReportSuccess('Der Youser wurde erfolgreich gelöscht');

		FrontController::Relocate('Index');
	}

	public function LoginAs()
	{
		Session::Set('Youser', 'old_id', Session::Get('Youser', 'id'));
		Session::Set('Youser', 'id', $_REQUEST['youser_id']);
		FrontController::Relocate(false);
	}
}
?>