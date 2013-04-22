<?php
class System_Plugins extends Controller
{
	public function Index()	{
	}

	public function Administration()
	{

	}

	public function Sites()
	{

	}

	public function Insert()
	{

	}

	public function Call()
	{
		$template = $this->get_template(false, PluginEngine::Call($_GET['plugin'], $_GET['action']));
	}
}
?>