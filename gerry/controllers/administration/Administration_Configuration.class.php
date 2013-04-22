<?php
class Administration_Configuration extends Controller
{
	public function Index_POST()
	{
		Config::SetConfiguration($_POST['configuration']);
		Dobber::ReportSuccess('Konfiguration wurde gespeichert');
		FrontController::Relocate('Index');
	}

	public function Index()
	{
		$template = $this->get_template(true);

		$config_keys = call_user_func_array('array_merge', Event::Dispatch('harvest', 'Config:Options'));
		$template->assign('config', Helper::ExtractOptions($config_keys));
	}
}
?>