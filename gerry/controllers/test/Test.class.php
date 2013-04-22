<?php
class Test extends Controller
{
	public function Plugins()
	{
		FrontController::GetURL();

		$template = $this->get_template(true);
	}

	public function ShadowBox()
	{
		$template = $this->get_template(true);
	}
}
?>