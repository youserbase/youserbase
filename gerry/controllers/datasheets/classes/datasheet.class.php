<?php
class datasheet
{
	public static $device_type = 'mobilephone';

	public static function Get($device_type = null)
	{
		if($device_type !== null)
		{
			self::$device_type = $device_type;
		}
		self::get_sheet_description();
	}
	
	private function get_sheet_description()
	{
		
	}
}
?>