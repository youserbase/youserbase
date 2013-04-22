<?php
class SearchResult_Device extends SearchResult
{
	public function get_type()
	{
		return 'Device';
	}

	public function identify()
	{
		return $this->data['device_id'];
	}

	public function render()
	{
		$path = realpath(dirname(__FILE__).'/../templates');
		$template = new Template($path.'/searchresult_device');
		$template->set_variables($this->data);
		if ($this->needle!==null)
		{
			$template->assign('needle', $this->needle);
		}
		return $template->render();
	}
}
?>