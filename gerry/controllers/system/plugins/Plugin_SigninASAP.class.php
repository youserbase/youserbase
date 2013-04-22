<?php
class Plugin_SigninASAP extends Plugin
{
	public function fill_template(&$template)
	{
		return !Youser::Id() or Youser::May('investigate');
	}

	public function has_header()
	{
		return false;
	}
}
?>