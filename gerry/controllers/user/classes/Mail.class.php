<?php
class Mail
{
	public static function get_mail_text($mail_type)
	{
		$language = BabelFish::GetLanguage();
		$mail_text = DBManager::Get('devices')->query("SELECT mail FROM mail_texts WHERE mail_type = ? AND language_id = ?;", $mail_type, $language)->fetch_item();
		if($mail_text == NULL)
		{
			$mail_text = DBManager::Get('devices')->query("SELECT mail FROM mail_texts WHERE mail_type = ? AND language_id = 'uk';", $mail_type)->fetch_item();
		}
		return $mail_text;
	}
	
	public static function set_mail_text()
	{
		
	}
}
?>