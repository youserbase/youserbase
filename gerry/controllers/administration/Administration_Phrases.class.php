<?php
class Administration_Phrases extends Controller
{
	public function Index()
	{
		if ($this->Posted('language'))
		{
			Session::Set('temp', 'language', $_POST['language']);
			FrontController::Relocate();
		}

		$template = $this->get_template(true);
		$template->set_position('TABS_RIGHT');
		$template->assign('current_language', $this->get_current_language());
		$template->assign('phrase_languages', BabelFish::GetLanguages());
	}

	public function Search()
	{
		$needle = isset($_POST['needle']) ? $_POST['needle'] : '';
		$phrase_id = !empty($needle) ? BabelFish::ReverseLookup($needle) : '';

		$template = $this->get_template(true);
		$template->assign('needle', $needle);
		$template->assign('phrase_id', $phrase_id);
	}

	public function Administration()
	{
		if ($this->Getted('delete', 'language'))
		{
			DBManager::Get()->query("DELETE FROM phrases WHERE phrase_id=? AND language=?", $_GET['delete'], $_GET['language']);
			if (DBManager::Get()->affected_rows()>0)
			{
				Dobber::ReportSuccess('Eintrag wurde erfolgreich gelöscht');
			}
		}
		elseif ($this->Posted('phrases', 'language'))
		{
			foreach ($_POST['phrases'] as $phrase_id=>$data)
			{
				BabelFish::UpdatePhrase($_POST['language'], $phrase_id, $data['phrase'], $data['id'], Youser::Id());
			}
			Dobber::ReportSuccess('Phrases wurden erfolgreich bearbeitet');
		}

		$count = BabelFish::GetPhraseCount($this->get_current_language());
		$current_page = (isset($_REQUEST['page']) and preg_match('/^\d+$/S', $_REQUEST['page']))
			? $_REQUEST['page']
			: 0;
		$current_page = max(0, min($current_page, ceil($count/Config::Get('pinboard:pagination_count'))-1));

		$template = $this->get_template(true);
		$template->assign('phrases', BabelFish::GetPhrases($this->get_current_language(), $current_page*Config::Get('phrases:pagination_count'), Config::Get('phrases:pagination_count')));
		$template->assign('phrases_count', $count);
		$template->assign('current_page', $current_page);
		$template->assign('current_language', $this->get_current_language());
	}

	public function Insert_POST()
	{
		if (!empty($_FILES['file']['name']))
		{
			if (!preg_match('/text\/(?:plain|tsv|csv)/', $_FILES['file']['type']))
			{
				Dobber::ReportError('Ungültiges Dateiformat "%s", nur CSV in UTF-8 erlaubt', $_FILES['file']['type']);
			}
			else
			{
				$fp = fopen($_FILES['file']['tmp_name'], 'r');
				$languages = array_map('strtolower', array_map('trim', array_slice(fgetcsv($fp), 1)));
				$used_languages = array();
				$phrases = 0;
				while ($row = fgetcsv($fp))
				{
					$phrases += 1;
					$phrase_id = strtoupper(array_shift($row));
					foreach ($languages as $index=>$language)
					{
						if (empty($row[$index]))
						{
							continue;
						}

						BabelFish::Update($language, $phrase_id, $row[$index], Youser::Id());

						$used_languages[$language] = true;
					}
				}
				fclose($fp);

				Dobber::ReportSuccess('%s Phrases in %s Sprachen wurden importiert', $phrases, count($used_languages));
			}
		}
		elseif (!$this->Posted('phrase', 'content', 'language'))
		{
			Dobber::ReportError('Es wurden nicht alle Felder ausgefüllt');
		}
		else
		{
			BabelFish::Update($_POST['language'], $_POST['phrase'], $_POST['content'], Youser::Id());
			Dobber::ReportSuccess('Der Eintrag wurde erfolgreich erstellt');
		}
		FrontController::Relocate();
	}

	public function Insert()
	{
		$template = $this->get_template(true);
		$template->assign('current_language', $this->get_current_language());
	}

	public function Missing()
	{
		if ($this->Posted('phrases', 'language'))
		{
			foreach ($_POST['phrases'] as $phrase_id=>$phrase)
			{
				if (empty($phrase))
				{
					continue;
				}
				BabelFish::InsertPhrase($_POST['language'], $phrase_id, $phrase);
			}
			Dobber::ReportSuccess('Die Phrases wurden erfolgreich eingetragen');
		}

		$count = BabelFish::GetMissingCount($this->get_current_language());
		$current_page = (isset($_REQUEST['page']) and preg_match('/^\d+$/S', $_REQUEST['page']))
			? $_REQUEST['page']
			: 0;
		$current_page = max(0, min($current_page, ceil($count/Config::Get('pinboard:pagination_count'))-1));

		$template = $this->get_template(true);
		$template->assign('missing_phrases', BabelFish::GetMissing($this->get_current_language(), $current_page*Config::Get('phrases:pagination_count'), Config::Get('phrases:pagination_count')));
		$template->assign('missing_count', $count);
		$template->assign('current_page', $current_page);
		$template->assign('current_language', $this->get_current_language());
	}

	private function get_current_language()
	{
		return Session::Get('temp', 'language')===null
			? BabelFish::GetLanguage()
			: Session::Get('temp', 'language');
	}

	public function SinglePhrase()
	{
		$template = $this->get_template(true);
		if ($this->Posted('needle'))
		{
			$languages = BabelFish::GetLanguages();

			$result = DBManager::Get()->query("SELECT phrase, language FROM phrases WHERE phrase_id=? AND language IN (?)", $_POST['needle'], $languages)->to_array('language', 'phrase');

			$template->assign('languages', $languages);
			$template->assign('result', $result);
		}
	}
}
?>