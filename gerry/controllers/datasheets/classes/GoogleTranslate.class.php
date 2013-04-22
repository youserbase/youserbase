<?php
class GoogleTranslate
{
	private static $instance = null;
	
	public static function Get(){
		if(self::$instance == null)
		{
			self::$instance = new self;			
		}
		return self::$instance;
	}
	
	/**
	 * The function delivers the language of the snippet handed over
	 *
	 * @param string $snippet The snippet of which the language shall be determined
	 * @return The language of the snippet
	 */
	public static function Language($snippet){
		$url = 'ajax.googleapis.com/ajax/services/language/detect?v=1.0&q='.urlencode($snippet).'&user_ip='.$_SERVER['REMOTE_ADDR'];
		$curlhandle = curl_init($url);
		curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, true);
		$language = curl_exec($curlhandle);
		curl_close($curlhandle);
		$language = str_replace(array('{"responseData": {', '}', '"', ' ', 'language:', 'isReliable:', 'confidence:'), '', $language);
		$language = explode(',', $language);
		return $language['0'];
	
	}
	
	
	/**
	 * The function delivers the translation of the snippet into the target language
	 *
	 * @param string $snippet The snippet that shall be translated
	 * @param string $target_language The language the snippet shall be translated to
	 * @return The Translation of the snippet
	 */
	public static function Translate($snippet){
		$source_language = self::Language($snippet);
		$target_language = BabelFish::GetLanguage();
		if($target_language == 'uk' || $target_language == 'us'){
			$target_language = 'en';
		}
		if ($source_language == $target_language) {
			return $snippet;
		}
		$snippet = str_replace(array('`', '´', "'"), "", $snippet);
		$url = 'ajax.googleapis.com/ajax/services/language/translate?v=1.0&q='.urlencode($snippet).'&langpair='.$source_language.'|'.$target_language.'&user_ip='.$_SERVER['REMOTE_ADDR'];
		$curlhandle = curl_init($url);
		curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, true);
		$translation = curl_exec($curlhandle);
		curl_close($curlhandle);
		$translation = substr($translation, strpos($translation, '{"translatedText":"')+strlen('{"translatedText":"'), strpos($translation, '}')-strpos($translation, '{"translatedText":"')-strlen('{"translatedText":" '));
		$translation = comment_handler::find_links($translation, true);
		return str_replace('\u0026#39;', "`",$translation);
	}
}
?>