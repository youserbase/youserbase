<?php
class Plugin_InviteFriends extends Plugin
{
	public function fill_template(&$template)
	{
		return Youser::Id();
	}
}
?>