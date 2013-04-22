<?php
class Mailer
{
	public static function SendTextMail($recipient, $from, $subject, $template, $data, $language=null)
	{
		$dbt = debug_backtrace();
		$template_path = dirname($dbt[0]['file']);

		if ($language===null)
		{
			$language = BabelFish::GetLanguage();
		}

		$template_file_template = '%s/%s/%s.php';

		$template_file = sprintf($template_file_template, $template_path, 'templates/mail', $template);
		if (!file_exists($template_file))
		{
			$template_file = sprintf($template_file_template, $template_path, '../templates/mail', $template);
		}
		if (!file_exists($template_file))
		{
			$template_file = sprintf($template_file_template, './templates/mail', $template);
		}
		if (!file_exists($template_file))
		{
			throw new Exception('No mail template');
		}

		$old_language = BabelFish::GetLanguage();
		BabelFish::SetLanguage($language);

		$template = new Template($template_file);
		$template->set_variables($data);
		$content = $template->fetch(true);

		BabelFish::SetLanguage($old_language);

		$headers = array(
			'Content-Transfer-Encoding'=>'8bit',
			'Content-Type'=>'text/plain; charset="UTF-8"'
		);

		self::Send($recipient, $from, $subject, $content, $headers);
	}

	public static function SendHtmlMail($recipient, $from, $subject, $template, $data, $language=null)
	{
		throw new Exception(__METHOD__.' is not yet implemented');
	}

	private static function Send($recipient, $from, $subject, $content, $headers)
	{
		$headers['MIME-Version'] = '1.0';

		$headers_to_send = 'From: '.$from."\r\n";
		foreach ($headers as $key=>$value)
		{
			$headers_to_send .= $key.': '.$value."\r\n";
		}
		mail($recipient,$subject, $content, $headers_to_send);
	}
}
?>