<?php
class SearchResult_User extends SearchResult
{
	public function get_type()
	{
		return 'User';
	}

	public function identify()
	{
		return $this->data['youser_id'];
	}

	public function render()
	{
		$path = realpath(dirname(__FILE__).'/../templates');
		$template = new Template($path.'/searchresult_user');
		$template->set_variables($this->data);
		if ($this->needle!==null)
		{
			$template->assign('needle', $this->needle);
		}
		return $template->render();
	}
}
?>