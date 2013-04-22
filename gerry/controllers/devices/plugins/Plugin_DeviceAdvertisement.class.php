<?php
class Plugin_DeviceAdvertisement extends Plugin
{
	public function fill_template(&$template)
	{
		$template->assign('position', 'rbox');
		return true;
	}

	public function fill_template_skyscraper(&$template)
	{
		$template->assign('position', 'skyscraper');
		return true;
	}

	public function has_header()
	{
		return false;
	}
}
?>