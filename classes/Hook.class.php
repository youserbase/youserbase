<?php
abstract class Hook
{
//	abstract public static $hooks;
	public static function &get_template($filename)
	{
		$dbt = debug_backtrace();
		$template = new Template(realpath(dirname($dbt[0]['file']).'/../templates').'/'.$filename);
		return $template;
	}
}
?>