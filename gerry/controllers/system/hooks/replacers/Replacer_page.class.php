<?php
class Replacer_page {
	const tag = 'page';
	private static $required_parameters = array(
		'id',
	);

	private static function get_template()
	{
		return new Template(dirname(__FILE__).'/templates/'.self::tag.'.php');
	}

	public static function Consume($match)
	{
		$template = self::get_template();
		$template->assign( Pages::GetPage($match['parameters']['id'], BabelFish::GetLanguage()) );
		$template->assign('page', $match['parameters']['id']);
		$template->assign('quiet', isset($match['parameters']['quiet']) and as_boolean($match['parameters']['quiet'], false));
		$template->assign('rbox', empty($match['parameters']['rbox']) or as_boolean($match['parameters']['rbox']));
		return $template->render();
	}
}
?>