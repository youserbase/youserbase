<?php
class disclaimer_text
{
	public static function get_disclaimer()
	{
		$language_id = BabelFish::GetLanguage();
		$disclaimer = dbManager::Get('devices')->query("SELECT disclaimer_id, disclaimer_text FROM disclaimer WHERE language_id = ?;", $language_id)->to_array('disclaimer_id', 'disclaimer_text');
		if($disclaimer == NULL)
		{
			$disclaimer = dbManager::Get('devices')->query("SELECT disclaimer_id, disclaimer_text FROM disclaimer WHERE language_id = 'uk';")->to_array('disclaimer_id', 'disclaimer_text');
		}
		return $disclaimer;
	}
	
	public static function set_disclaimer()
	{
		$disclaimer = new disclaimer();
		$language_id = BabelFish::GetLanguage();
		$youser_id = Youser::Id();
		if(!isset($_POST['disclaimer_text']))
		{
			return false;
		}
		if(isset($_POST['disclaimer_id']) && !empty($_POST['disclaimer_id']))
		{
			$disclaimer->disclaimer_id = $_POST['disclaimer_id'];
		}
		$text = $_POST['disclaimer_text'];
		$disclaimer->disclaimer_text = $text;
		$disclaimer->language_id = $language_id;
		$disclaimer->youser_id = $youser_id;
		$disclaimer->save();
		Dobber::ReportNotice('DISCLAIMER_SET');
	}
	
	public static function cleaner($dirty)
	{
		$clean = strip_tags(htmlspecialchars($dirty));
		return $clean;
	}
}
?>