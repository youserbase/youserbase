<?php
class Hook_Permissions extends Hook
{
	public static $hooks = array(
		'Controller:Execute'=>'CheckPermission'
	);

	public static function CheckPermission($location)
	{
		if (!Permissions::CheckPermission(Youser::GetRole(), $location))
		{
			Dobber::ReportError('ERROR_INVALID_RIGHTS');
			FrontController::Relocate('System', 'System', 'Error', array('original'=>FrontController::GetLocation('/')));
		}
	}
}
?>