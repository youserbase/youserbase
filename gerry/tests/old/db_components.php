<?php
	Header('Content-Type: text/plain');
	error_reporting(E_ALL);

	require '../../includes/bootstrap.inc.php';
	restore_error_handlers();

	DBManager::SelectScope('devices');

	$new_component_ids = DBManager::Query("SELECT component_id_char, component_id_int FROM _component_mapping")->to_array('component_id_char', 'component_id_int');

//	var_dump($new_component_ids);

//	$new_device_ids = DBManager::Get()->query("SELECT device_id_char, device_id_int FROM _device_mapping")->to_array('device_id_char', 'device_id_int');

	foreach (DBManager::Query("SHOW TABLES")->to_array(null, ':1') as $table)
	{
		$result = DBManager::Query("DESC {$table}")->to_array('Field', 'Type');
		if ($table!='device' and array_key_exists('device_id', $result))
		{
			DBManager::Query("DELETE FROM `{$table}` WHERE device_id NOT IN (SELECT device_id FROM device)");
		}

		if (array_key_exists('component_id', $result))
		{
			$deleted = ($table=='device_components')
				? 0
				: DBManager::Query("DELETE FROM `{$table}` WHERE component_id NOT IN (SELECT component_id FROM device_components)");

			$changed = 0;
			echo $table.': ';

			if (!array_key_exists('component_id_int', $result))
			{
				DBManager::Query("ALTER TABLE `{$table}` ADD `component_id_int` BIGINT( 20 ) UNSIGNED NOT NULL AFTER `component_id`");
			}

			$component_ids = DBManager::Query("SELECT component_id FROM `{$table}`")->to_array(null, 'component_id');
			foreach ($component_ids as $component_id)
			{
				$changed += DBManager::Query("UPDATE `{$table}` SET component_id_int=? WHERE component_id=?",
					$new_component_ids[ $component_id ],
					$component_id
				);
			}
			echo number_format($changed,0,',','.')." (".number_format($deleted,0,',','.').")"."\n";
			flush();
		}
	}
?>