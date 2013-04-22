<?php
class Debug extends Controller
{
	public function Explain()
	{
		$explained = DBManager::Get($_REQUEST['scope'])->query("EXPLAIN EXTENDED ".$_REQUEST['query'])->to_array();
		$warnings = DBManager::Get($_REQUEST['scope'])->query("SHOW WARNINGS")->to_array();

		$template = $this->get_template(false, '<pre>'.array_to_table($explained).array_to_table($warnings).'</pre>');
	}

	public function Toggle()
	{
		Session::Set('debugmode', !Session::Get('debugmode'));
		$this->get_template(false, json_encode(array('debugmode'=>!!Session::Get('debugmode'))));
	}

	public function ToggleHarvester()
	{
		Config::Set('system', 'harvest_mode', !Config::Get('system', 'harvest_mode'));
		$this->get_template(false, '');
	}
}
?>