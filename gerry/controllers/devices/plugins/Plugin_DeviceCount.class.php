<?php
class Plugin_DeviceCount extends Plugin
{
//	public static $link_location = array('datasheets', 'Datasheets_Browsing', 'browseBest', array('#'=>'boxy'));

	public function fill_template(&$template)
	{
		$template->assign('count', DeviceHelper::GetDeviceCount());
		$template->assign('picture_count', DeviceHelper::GetDevicePictureCount());

		return true;
	}
}
?>