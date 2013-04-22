<?php
class Permissions_Administration extends Controller
{
	public function Index_POST()
	{
		foreach ($_POST['permissions'] as $location=>$permissions)
		{
			Permissions::SetPermission($location, $permissions);
		}
		FrontController::Relocate('Index');
	}

	public function Index()
	{
		$template = $this->get_template(true);
		$template->assign('roles', Permissions::GetRoles());
		$template->assign('permissions', Permissions::GetPermissions(null, true));
	}

	public function Delete()
	{
		if (empty($_GET['location']))
		{
			Dobber::ReportError('Missing parameters for '.__METHOD__);
			FrontController::Relocate('Index');
		}
		Permissions::DeletePermission($_GET['location']);
		FrontController::Relocate('Index');
	}

	public function Insert_POST()
	{
		if ($this->Posted('location', 'permissions'))
		{
			Dobber::ReportSuccess('Die Berechtigung wurde erfolgreich eingetragen');
			Permissions::SetPermission($_POST['location'], $_POST['permissions']);
			FrontController::Relocate();
		}
		Dobber::ReportError('Es wurden nicht alle Felder ausgefüllt');
		FrontController::Relocate();
	}

	public function Insert()
	{
		$template = $this->get_template(true);
		$template->assign('actions', FrontController::GetAvailableActions(false, true));
		$template->assign('permissions', Permissions::GetRoles());
	}
}
?>