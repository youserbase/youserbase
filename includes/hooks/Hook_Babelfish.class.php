<?php
class Hook_Babelfish extends Hook
{
	private static $param_regexp = '/\s(?P<key>.+?)="(?P<value>.*?)"/imsS';

	public static $hooks = array(
		'System:Wakeup'=>'SetLanguage',
		'Youser:Login'=>'SetLanguage',
		'Template:Fetch:After'=>array(
			'TranslateString',
			'StorePhrases'
		),
		'Config:Options'=>'GetOptions'
	);

	public static function GetOptions()
	{
		return array(
			'system:translation_mode:bool',
			'memory:phrases_stored:range:3,25'
		);
	}

	public static function StorePhrases($content)
	{
		if (!Youser::May('translate'))
		{
			Session::Clear('memory', 'phrases');
			return $content;
		}

		$stored_phrases = (array)Session::Get('memory', 'phrases');

		$keys = array_keys($stored_phrases);
		$key = $GLOBALS['VIA_AJAX']
			? end($keys)
			: Helper::GetNonce();

		if (!isset($stored_phrases[$key]))
		{
			$stored_phrases[$key] = array(
				'translated'=>array(),
				'untranslated'=>array()
			);
		}

		$stored_phrases[$key]['translated'] = array_unique(array_merge(
			$stored_phrases[$key]['translated'],
			array_keys(BabelFish::GetCache())
		));
		$stored_phrases[$key]['untranslated'] = array_unique(array_merge(
			$stored_phrases[$key]['untranslated'],
			BabelFish::GetUntranslated()
		));

		Header('X-Babelfish: '.json_encode(array(
			'translated'=>count($stored_phrases[$key]['translated']),
			'untranslated'=>count($stored_phrases[$key]['untranslated'])
		)));

		if (count($stored_phrases)>Config::Get('memory:phrases_stored'))
		{
			$stored_phrases = array_slice($stored_phrases, -Config::Get('memory:phrases_stored'));
		}

		Session::Set('memory', 'phrases', $stored_phrases);

		return $content;
	}

	public static function SetLanguage($youser_id=null)
	{
		$language = null;

		if (!empty($_GET['preferred_language']))
		{
			$language = $_GET['preferred_language'];
		}
		elseif ($youser_id!==null)
		{
			$language = Youser::Get($youser_id)->language;
		}

		BabelFish::SetLanguage($language);
	}

	private static function ExtractPhraseIds($array, $index = 1)
	{
		$phrase_ids = array();
		foreach ($array as $item)
		{
			array_push($phrase_ids, isset($item['phrase_id'])
				? $item['phrase_id']
				: $item[$index]
			);
		}
		return $phrase_ids;
	}

	// TODO: Heavy optimization required, TagParser must be implemented (Replacer?)
	public static function TranslateString($content, $text_only=false)
	{
		Timer::Report('BabelFish: Wakeup');
		$text_only = ($text_only or !Youser::Id() or !Youser::May('translate'));

		// Gain all explicit phrase tags
		Timer::Report('BabelFish: RegExp 1');
		$matched = preg_match_all('/<phrase id="(.+?)"([^>]*)\/>/u', $content, $matches_standalone, PREG_SET_ORDER);
		Timer::Report('BabelFish: ..%d matches', count($matches_standalone));

		// Gain all attribute modifiers
		Timer::Report('BabelFish: RegExp 2');
		$matched += preg_match_all('/\s(alt|label|title|value)_phrase="(.+?)"(?=[^>]*>)/u', $content, $matches_attributes, PREG_SET_ORDER);
		Timer::Report('BabelFish: ..%d matches', count($matches_attributes));

		$phrase_ids = array_merge(
			self::ExtractPhraseIds($matches_standalone),
			self::ExtractPhraseIds($matches_attributes, 2)
		);

		if ($matched==0)
		{
			return str_replace(array('<!--BabelFish:TranslatedCount-->', '<!--BabelFish:UntranslatedCount-->'), '0', $content);
		}

		Timer::Report('BabelFish: Retrieve phrases');
		$translations = BabelFish::Get($phrase_ids);
		Timer::Report('BabelFish: Retrieved phrases');
		$final_translations = array();

		// Replace all explicit phrase tags
		Timer::Report('BabelFish: (phrase/)');
		foreach ($matches_standalone as $match)
		{
			$parameters = new Parameters($match[2]);

			$language = $parameters->get('language')
				? $parameters->get('language')
				: BabelFish::GetLanguage();

			if ($language != BabelFish::GetLanguage())
			{
				$translations[$match[1]] = BabelFish::Get($match[1], $language);
			}

			if (!empty($translations[$match[1]]))
			{
				$translation = Template::Interpolate($translations[$match[1]], $parameters->get());

				if ($parameters->get_boolean('urlencode', false))
				{
					$translation = urlencode($translation);
				}
				if ($parameters->get_boolean('entities', false))
				{
					$translation = htmlspecialchars($translation, ENT_NOQUOTES, 'UTF-8');
				}
				if (!$parameters->get_boolean('quiet', false))
				{
					$translation = sprintf('<span class="phrase stored:phrase_id:%s stored:language:%s" title="%s">%s</span>',
						$match[1],
						$language,
						$match[1],
						$translation
					);
				}
				if (!$parameters->is_empty('highlight'))
				{
					$translation = preg_replace('/('.preg_quote($parameters->get('highlight')).')(?=[^>]*<)/i', '<span class="highlight">$1</span>', $translation);
				}
			}
			else
			{
				$params = '';
				foreach ($parameters->get() as $key=>$value)
				{
					$params .= ','.$key.'='.$value;
				}
				if ($parameters->get_boolean('urlencode', false))
				{
					$translation = urlencode($translation);
				}
				if ($parameters->get_boolean('quiet', false))
				{
					$translation = '<span class="untranslated phrase stored:phrase_id:'.$match[1].' stored:language:'.$language.'" title="'.$match[1].$params.'">'.$match[1].'</span> ';
				}
				else
				{
					$translation = $match[1].$params;
				}
			}
			$final_translations[$match[0]] = $translation;
		}

		// Replace all attribute modifiers
		Timer::Report('BabelFish: (tag alt_phrase=""/)');
		foreach ($matches_attributes as $match)
		{
			if (isset($final_translations[$match[0]]))
			{
				continue;
			}

			$translation = !empty($translations[$match[2]])
				? $translations[$match[2]]
				: $match[2];

			$translation = ' '.$match[1].'="'.$translation.'"';

			$final_translations[$match[0]] = $translation;
		}


		if ($text_only)
		{
			$final_translations = array_map('strip_tags', $final_translations);
		}

		Timer::Report('BabelFish: Between');

		$final_translations = array_merge($final_translations, array(
			'<!--BabelFish:TranslatedCount-->'=>'<span id="babelfish--translated">'.numberformat(count(BabelFish::GetCache())).'</span>',
			'<!--BabelFish:UntranslatedCount-->'=>'<span id="babelfish--untranslated">'.numberformat(count(BabelFish::GetUntranslated())).'</span>'
		));

		Timer::Report('BabelFish: Before replace');
		$content = str_replace(array_keys($final_translations), array_values($final_translations), $content);
		Timer::Report('BabelFish: After replace');

		return $content;
	}
}
?>