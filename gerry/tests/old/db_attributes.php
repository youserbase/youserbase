<?php
	Header('Content-Type: text/plain');
	error_reporting(E_ALL);

	require '../../includes/bootstrap.inc.php';
	restore_error_handlers();

	DBManager::SelectScope('devices');

	foreach (DBManager::Query("SHOW TABLES")->to_array(null, ':1') as $table)
	{
		if (preg_match('/_type$/', $table) or in_array($table, array('input_method', 'manufacturer_device_types', 'secondary_display')))
		{
			continue;
		}
		$result = DBManager::Query("DESC {$table}")->to_array('Field', 'Type');
		foreach ($result as $field => $dummy)
		{
			if (!preg_match('/_type_id$/', $field))
			{
				continue;
			}
			echo $table."/".$field.": ";
			$changed = 0;

			if (!array_key_exists($field.'_int', $result))
			{
				DBManager::Query("ALTER TABLE `{$table}` ADD `{$field}_int` BIGINT( 20 ) UNSIGNED NOT NULL AFTER `$field`");
			}

			$type_table = preg_replace('/_id$/', '', $field);
			$ids = DBManager::Query("SELECT `{$field}`, `{$field}_int` FROM `{$type_table}`")->to_array($field, $field.'_int');
			foreach ($ids as $old=>$new)
			{
				$changed += DBManager::Query("UPDATE `{$table}` SET `{$field}_int`=? WHERE `{$field}`=?",
					$new,
					$old
				);
			}

			echo number_format($changed,0,',','.')."\n";
			flush();
		}
	}
?>