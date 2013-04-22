<?php
class Administration_Plugins extends Controller
{
	public function Index()
	{
		$template = $this->get_template(false);
	}

	public function Administration()
	{
		$template = $this->get_template(true);
	}

	public function Configuration_POST()
	{
		Config::SetConfiguration($_POST['configuration'], 'plugin');
		Dobber::ReportSuccess('Plugin-Konfiguration wurde gespeichert');

		return false;
	}

	public function Configuration()
	{
		$options = empty($_REQUEST['plugin_name'])
			? PluginEngine::GetOptions()
			: PluginEngine::GetOptions($_REQUEST['plugin_name']);

		$template = $this->get_template(true);
		$template->assign('options', Helper::ExtractOptions($options));
	}
}
?>