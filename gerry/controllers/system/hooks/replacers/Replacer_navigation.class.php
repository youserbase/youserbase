<?php
class Replacer_navigation {
	const tag = 'navigation';

	public static function Consume($match)
	{
		return Navigation::Render($match['parameters']['id']);
	}
}
?>