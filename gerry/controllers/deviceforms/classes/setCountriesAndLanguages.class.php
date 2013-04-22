<?php
class setCountriesAndLanguages
{
	public function startReading()
	{
		if (isset($_FILES['file']))
		{
			$filename = $_FILES['file']['tmp_name'];
			if (file_exists($filename))
			{
				$file = file_get_contents($filename);
				$file = explode('/', $file);
				foreach ($file as $line)
				{
					if(str_word_count($line)>0)
					{
						list($country, $lang) = explode(';', $line);
						$language = $this->getPrimaryLanguage($lang);
						
						$countries[$country] = $language;
						$languages[] = $language;
					}
				}
				$this->insert($countries, $languages);
			}
		}
		else echo 'Nichts angekommen';
	}
	
	private function getPrimaryLanguage($languages)
	{
		$languages = explode(',', $languages);
		$languagae = explode(' ', $languages[0]);
		$primary = $languagae[0];
		return $primary;
		
	}
	
	private function insert($countries, $languages)
	{
		foreach ($languages as $language)
		{
			$id = md5(uniqid($language.time()));
			$language = new language($id, $language);
			$language->save();
		}
		$db = DBManager::Get('devices');
		foreach ($countries as $country => $language)
		{
			$result = $db->query('SELECT language_id FROM language WHERE language LIKE ?', $language);
			if(!$result->is_empty())
			{
				while ($data = $result->fetch_array())
				{
					$language_id = str_replace("\"", '', $data['language_id']);
				}
			}
			$country_id = md5(uniqid($country.time()));
			$cl_id = md5(uniqid($country.$language.time()));
			$country = str_replace("\r", "", $country);
			$c = new country($country_id, $country);
			$c->save();
			$country_language = new countrylanguages($cl_id, $language_id, $country_id);
			$country_language->save();
		}
	}
}
?>