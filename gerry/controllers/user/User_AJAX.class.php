<?php
class User_AJAX extends Controller
{
	public function Poll()
	{
		$GLOBALS['deny_log'] = true;

		$data = call_user_func_array('array_merge', Event::Dispatch('harvest', 'User:Poll', Youser::Id()));
		$template = $this->get_template(false, json_encode($data));
	}
}
?>