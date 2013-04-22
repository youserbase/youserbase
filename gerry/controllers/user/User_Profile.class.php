<?php
class User_Profile extends Controller
{
	public function Index()
	{
		$template = $this->get_template(true);
		$template->assign('friendrequests', Event::Dispatch('grab', 'Plugin:FriendRequest', Youser::Id()));
		$template->assign('activities', Event::Dispatch('grab', 'Plugin:Activity', Youser::Id()));
	}

	public function PasswordChange()
	{
		$youser = Youser::Get();
		if (!$this->Posted('old_password', 'new_password', 'new_password_confirm'))
			Dobber::ReportError('ERROR_FORM_INCOMPLETE_DATA');
		elseif (!Youser::Validate($youser->nickname, $_POST['old_password']))
			Dobber::ReportError('ERROR_FORM_INVALID_DATA');
		elseif ($_POST['new_password']!=$_POST['new_password_confirm'])
			Dobber::ReportError('ERROR_FORM_PASSWORD_NOT_CONFIRMED');
		else
		{
			$youser->setPassword($_POST['new_password']);
			if ($youser->save())
				Dobber::ReportSuccess('SUCCESS_PERSONAL_SETTINGS_SAVED');
		}
		FrontController::Relocate('Settings');
	}

	public function Settings()
	{
		$youser = Youser::Get();
		if ($this->Posted())
		{
			$all_set = array_reduce(array('email', 'language'),
				create_function('$v, $w', 'return !empty($_POST[$w]) ? $v : false;'), true);
			if (!$all_set)
				Dobber::ReportError('ERROR_FORM_INCOMPLETE_DATA');
			elseif ($youser->email != $_POST['email'] and Youser::EmailExists($_POST['email']))
				Dobber::ReportError('ERROR_FORM_EMAIL_TAKEN');

			if (Dobber::IsEmpty('error'))
			{
				if ($youser->email != $_POST['email'])
				{
					$confirmation_key = Confirmation::TimedRequest('emailchange', $youser->id, 24*60*60, $_POST['email']);
					Mailer::SendTextMail($_POST['email'], Config::Get('mail:youserbase_from'), /* PHRASE */'Änderung Ihrer eMail-Adresse', 'emailchange', array(
						'nickname'=>$youser->nickname,
						'link'=>FrontController::GetAbsoluteURI().FrontController::GetLink('System', 'System', 'Confirm', array('key'=>$confirmation_key, 'preferred_language'=>false))
					));

					Dobber::ReportNotice('PROMPT_CONFIRM_REGISTRATION_EMAIL');
				}
				$youser->visible = !empty($_POST['visible']);
				if ($_POST['language']!=$youser->language)
				{
					$youser->language = $_POST['language'];
					BabelFish::SetLanguage($youser->language);
				}

				if ($youser->save())
				{
					Dobber::ReportSuccess('SUCCESS_PERSONAL_SETTINGS_SAVED');
					FrontController::Relocate();
				}
			}
		}
		$template = $this->get_template(true);
		$template->assign('youser', $youser);
		$template->assign('youser_languages', BabelFish::GetLanguages());
	}

	public function Picture()
	{
		if (!empty($_POST['delete_picture']))
			Youser_Image::Remove( Youser::Id() );
		elseif (!empty($_FILES['youser_picture']['name']))
			Youser_Image::Upload( Youser::Id(), $_FILES['youser_picture']);

		FrontController::Relocate('Modify');
	}

	public function Modify_POST()
	{
		$youser = Youser::Get();

		foreach ($_POST['profile'] as $category=>$data)
			foreach ($data as $name=>$item)
				$youser->set_attribute($category.':'.$name, $item['value'], 'visible');
		Dobber::ReportSuccess('SUCCESS_PERSONAL_PROFILE_SAVED');
		FrontController::Relocate();

	}

	public function Modify()
	{
		$youser = Youser::Get();

		$attributes = array();
		foreach ($youser->get_attribute(null) as $attribute=>$data)
		{
			list ($category, $key) = explode(':', $attribute);
			if (!isset($attributes[$category]))
				$attributes[$category] = array();
			$attributes[$category][$key] = $data;
		}

		$layout = array();
		foreach (array_filter(explode("\n", Config::Get('profile:layout'))) as $entry)
		{
			if ($entry{0}=='#')
				continue;

			$config = explode(':', trim($entry));
			if (!isset($layout[$config[0]]))
				$layout[$config[0]] = array();
			$layout[$config[0]][$config[1]] = array_slice($config, 2);

			if (!isset($attributes[$config[0]]))
				$attributes[$config[0]] = array();
			if (!isset($attributes[$config[0]][$config[1]]))
				$attributes[$config[0]][$config[1]] = array('value'=>'', 'visibility'=>'visible');
		}

		$template = $this->get_template(true);
		$template->assign('youser', $youser);
		$template->assign('attributes', $attributes);
		$template->assign('layout', $layout);
	}
}
?>