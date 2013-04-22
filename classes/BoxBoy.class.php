<?php
class BoxBoy
{
	public static function Prepare($content)
	{
		return nl2br(htmlentities($content, ENT_QUOTES, 'UTF-8'));
	}

	public static function RemoveSpecialCharacters($string)
	{
		return str_replace(array('ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß'), array('ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss'), $string);
	}
}
?>