<?php
class System_Statistics extends Controller
{
	public function Index()
	{
		var_dump(Event::GetHooks('Navigation:Plugin', true));

		$template = $this->get_template(true);
		$template->assign('hooks', Event::GetHooks());
	}
}
?>