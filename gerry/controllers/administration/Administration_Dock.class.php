<?php
class Administration_Dock extends Controller
{
	public function Plugins_POST()
	{
		$plugins = array();
		foreach ($_REQUEST['plugins'] as $index=>$plugin)
		{
			if (!empty($_REQUEST['plugin_methods'][$index]))
			{
				$plugin .= ':'.$_REQUEST['plugin_methods'][$index];
			}
			array_push($plugins, $plugin);
		}

		Sitemap::UpdatePlugins($_REQUEST['site_id'], $plugins, isset($_REQUEST['scope'])?$_REQUEST['scope']:null);
		Dobber::ReportSuccess('Pluginkonfiguration wurde erfolgreich gespeichert');

		return false;
	}

	public function Plugins()
	{
		$template = $this->get_template(true);
		$template->assign('plugins', PluginEngine::GetPluginNames());
		$template->assign('site_plugins', Sitemap::GetPlugins($_GET['site_id'], isset($_REQUEST['scope'])?$_REQUEST['scope']:null));
		$template->assign('site_id', $_GET['site_id']);
		$template->assign('scope', empty($_REQUEST['scope'])?'plugins':$_REQUEST['scope']);
	}

	public function Tabs_POST()
	{
		Sitemap::SetTabs($_REQUEST['site_id'], $_REQUEST['tabs']);
		Dobber::ReportSuccess('Tabkonfiguration wurde erfolgreich gespeichert');
		return false;
	}

	public function Tabs()
	{
		$template = $this->get_template(true);
		$template->assign('all_tabs', FrontController::GetAvailableActions());
		$template->assign('site_tabs', Sitemap::GetTabs($_GET['site_id']));
		$template->assign('site_id', $_GET['site_id']);
	}

	public function Tabs_Load()
	{
		$this->get_template(false, json_encode(Sitemap::GetTabs($_GET['site_id'])));
	}

	public function Settings_POST()
	{
		Sitemap::UpdateParentSite($_REQUEST['site_id'], $_REQUEST['site']=='-1'?null:$_REQUEST['site']);
		Sitemap::UpdateNavigation($_REQUEST['site_id'], $_REQUEST['navigation']=='-1'?null:$_REQUEST['navigation']);
		Sitemap::SetMetaDescriptionPhraseId($_REQUEST['site_id'], empty($_REQUEST['meta_description'])?null:$_REQUEST['meta_description']);
		Sitemap::SetMetaKeywordsPhraseId($_REQUEST['site_id'], empty($_REQUEST['meta_keywords'])?null:$_REQUEST['meta_keywords']);

		Dobber::ReportSuccess('Änderungen wurden erfolgreich übernommen');
		Lightbox::Close();
		$this->get_template(false, '');
	}

	public function Settings()
	{
		$template = $this->get_template(true);
		$template->assign('site_id', $_REQUEST['site_id']);
	}
}
?>