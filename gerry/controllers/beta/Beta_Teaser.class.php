<?php
class Beta_Teaser extends Controller
{
	public function Index_POST()
	{
		if (!$youser_id=Youser::Validate($_POST['nickname'], $_POST['password']))
		{
			Dobber::ReportError('INVALID_NICKNAME_PASSWORD_COMBINATION');
		}
		else
		{
			Youser::Login($youser_id, empty($_POST['autologin']) ? 0 : (strtotime('+1 month')-time()));
			Dobber::ReportSuccess('SUCCESS_LOGGED_IN');
		}
		if (isset($_POST['return_to']))
		{
			FrontController::DirectRelocate($_POST['return_to']);
		}
		FrontController::Relocate();
	}

	public function Index()
	{
		$this->get_template(true)->set_layout('layouts/gerry_teaser.php');
	}

	public function Aspire()
	{
		if (!$this->Posted('email'))
		{
			Dobber::ReportError('PROMPT_EMAIL');
		}
		else
		{
			DBManager::Get()->query("INSERT IGNORE INTO beta_aspirants (email, timestamp) VALUES (TRIM(?), NOW())", $_POST['email']);
			Session::Set('beta_aspired', true);
		}
		if (isset($_POST['return_to']))
		{
			FrontController::DirectRelocate($_POST['return_to']);
		}
		FrontController::Relocate('Index');
	}
}
?>