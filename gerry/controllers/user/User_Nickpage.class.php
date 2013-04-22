<?php
class User_Nickpage extends Controller
{
	public function Display()
	{
		if (!$this->Getted('youser_id') and Youser::Get()===false)
		{
			Dobber::ReportError('Unberechtigter Zugriff');
			FrontController::Relocate('System', 'System', 'Error');
		}

		if ($this->Getted('youser_id'))
		{
			$youser = Youser::Get($_GET['youser_id']);
		}
		else
		{
			$youser = Youser::Get();
		}

		$attributes = array();
		foreach ($youser->get_attribute(null) as $attribute => $data)
		{
			list ($category, $key) = explode(':', $attribute);
			if (!isset($attributes[$category]))
			{
				$attributes[$category] = array();
			}
			$attributes[$category][$key] = $data;
		}

		$profile = array();
		$layout = array_filter(explode("\n", Config::Get('profile:display_layout')));
		foreach ($layout as $entry)
		{
			if ($entry{0}=='#')
			{
				continue;
			}

			$config = explode(':', trim($entry));

			$scope = array_shift($config);
			if (!isset($profile[$scope]))
			{
				$profile[$scope] = array();
			}

			$key = array_shift($config);
			$index = ($key{0}=='$')
				? substr($key, 1)
				: $key;

			$value = empty($attributes[$scope][$index])
				? ''
				: $attributes[$scope][$index]['value'];
			$type = 'plain';

			while ($option = array_shift($config))
			{
				switch ($option)
				{
					case '&':
						$other_scope = array_shift($config);
						$other_index = array_shift($config);
						if (!empty($attributes[$other_scope][$other_index]))
						{
							$value .= ' '.$attributes[$other_scope][$other_index]['value'];
						}
						break;
					case 'date':
						$value = strtotime($value);
						$type = 'date';
						break;
					case 'phrase':
						$type = 'phrase';
						break;
					default:
						$value .= ' '.Template::Interpolate($option, compact('youser'));
						break;
				}
			}

			$value = trim($value);
			if (!empty($value))
			{
				$profile[$scope][$index] = compact('value', 'type');
			}
		}

		$template = $this->get_template(true);
		$template->assign('youser', $youser);
		$template->assign('attributes', $attributes);
		$template->assign('profile', $profile);
		$template->assign('connection', Connection::Find(Youser::Id(), $youser->id));
	}

	public function AddEntry()
	{
		if ($this->Posted('message'))
		{
			Pinboard::Add($_POST['youser_id'], Youser::Id(), $_POST['message']);
			Dobber::ReportSuccess('SUCCESS_ADDED_PINBOARD_ENTRY');

			$params = array(
				'nickname'=>Youser::Get($_POST['youser_id'])->nickname,
				'sender'=>Youser::Get()->nickname
			);
			if ($_POST['youser_id']!=Youser::Id())
			{
				Mailer::SendTextMail(Youser::Get($_POST['youser_id'])->email, Config::Get('mail:youserbase_from'), /* PHRASE */'Neuer Pinnwandeintrag', 'pinboard_newentry', $params, Youser::Get($_POST['youser_id'])->language);
			}

			Event::Dispatch('alert', 'Youser:PinboardEntry', Youser::Id(), $_POST['youser_id']);

			if ($this->via_ajax)
			{
				Lightbox::Close();
				$this->get_template(false, '');
				return;			}
			else
			{
				FrontController::Relocate('Display');
			}
		}
		$template = $this->get_template(true);
		$template->assign('youser_id', $_REQUEST['youser_id']);
		$template->assign('nickname', Youser::Get($_REQUEST['youser_id'])->nickname);
	}

	public function Pinboard($youser_id=null)
	{
		if ($youser_id!==null)
		{
			$youser = Youser::Get($youser_id);
		}
		elseif ($this->Getted('youser'))
		{
			$youser = Youser::Get($_GET['youser'], true);
		}
		elseif ($this->Getted('youser_id'))
		{
			$youser = Youser::Get($_GET['youser_id']);
		}
		else
		{
			$youser = Youser::Get();
		}

		if (isset($_REQUEST['delete']))
		{
			if ($youser->id==Youser::Id() and Pinboard::Delete(Youser::Id(), $_REQUEST['delete']))
			{
				Dobber::ReportSuccess('SUCCESS_ENTRY_REMOVED');
			}
			elseif (Pinboard::DeleteSender($youser->id, Youser::Id(), $_REQUEST['delete']))
			{
				Dobber::ReportSuccess('SUCCESS_PINBOARD_ENTRY_REMOVED', array('nickname'=>$youser->nickname));
			}
		}

		$count = Pinboard::GetCount($youser->id, $youser->id==Youser::Id());
		$current_page = (isset($_REQUEST['page']) and preg_match('/^\d+$/S', $_REQUEST['page']))
			? $_REQUEST['page']
			: 0;
		$current_page = max(0, min($current_page, ceil($count/Config::Get('pinboard:pagination_count'))-1));

		$template = $this->get_template(true);
		$template->assign('youser_id', $youser->id);
		$template->assign('nickname', $youser->nickname);
		$template->assign('current_page', $current_page);
		$template->assign('pinboard_count', $count);
		$template->assign('pinboard', Pinboard::Get($youser->id, $youser->id==Youser::Id(), $current_page*Config::Get('pinboard:pagination_count'), Config::Get('pinboard:pagination_count')));
	}
}
?>