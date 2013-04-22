<?php
class System_Administration extends Controller
{
	public function Maintenance()
	{
		$template = $this->get_template(true);
		$template->assign('cache_count', count(glob(Cache::GetDirectory().'/*.*')));
	}

	public function ClearCache()
	{
		array_map('unlink', glob(Cache::GetDirectory().'/*.*'));
		foreach (array('css','js','simplepie','sitemap','system') as $directory)
			array_map('unlink', glob(Cache::GetDirectory($directory).'/*.*'));
		FrontController::Relocate('Maintenance');
	}

	public function ClearSessions()
	{
		DBManager::Get()->query("TRUNCATE TABLE sessions");
		FrontController::Relocate('Maintenance');
	}

	public function ClearStatistics()
	{
		DBManager::Get()->query("TRUNCATE TABLE statistics_daily");
		DBManager::Get()->query("TRUNCATE TABLE statistics_daily_browser");
		DBManager::Get()->query("TRUNCATE TABLE statistics_daily_devices");
		DBManager::Get()->query("TRUNCATE TABLE statistics_daily_languages");
		DBManager::Get()->query("TRUNCATE TABLE statistics_temp_daily_sessions");
		DBManager::Get()->query("TRUNCATE TABLE statistics_temp_daily_yousers");
		FrontController::Relocate('Maintenance');
	}

	public static function WipeDeviceImages()
	{
		if (!Youser::May('delete_devices'))
		{
			Dobber::ReportError('You are not allowed to do this');
			FrontController::Relocate('Maintenance');
		}
//		array_map('unlink', glob('assets/device_images/*/*.*'));
//		array_map('rmdir', glob('assets/device_images/*', GLOB_ONLYDIR));
//		DBManager::Get('devices')->query("TRUNCATE TABLE device_pictures");
		DBManager::Get('devices')->query("INSERT INTO who_did_id (youser_id, what_was_done, timestamp) VALUES (?, ?, NOW())",
			Youser::Id(),
			'delete_all_images'
		);
		FrontController::Relocate('Maintenance');
	}

	public static function ProcessPictures()
	{
		DBManager::Get('devices')->query("UPDATE device_pictures SET angle = 'front' WHERE original_filename LIKE 'front%'");
		DBManager::Get('devices')->query("UPDATE device_pictures SET angle = 'back' WHERE original_filename LIKE 'back%'");
		DBManager::Get('devices')->query("UPDATE device_pictures SET angle = 'left' WHERE original_filename LIKE 'angle_left%' OR original_filename LIKE 'left%'");
		DBManager::Get('devices')->query("UPDATE device_pictures SET angle = 'right' WHERE original_filename LIKE 'angle_right%' OR original_filename LIKE 'right%'");
		DBManager::Get('devices')->query("UPDATE device_pictures SET angle = 'multi' WHERE original_filename LIKE 'multi%'");

		DBManager::Get('devices')->query("UPDATE device_pictures SET situation = 'open' WHERE original_filename RLIKE '(_|-)open(_|\.)'");

		Dobber::ReportSuccess('All pictures processed');
		FrontController::Relocate('Maintenance');
	}
}
?>