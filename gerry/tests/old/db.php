<?php
	Header('Content-Type: text/plain');
	error_reporting(E_ALL);

	require '../../includes/bootstrap.inc.php';
	restore_error_handlers();

	DBManager::SelectScope('devices');

	foreach (DBManager::Query("SHOW TABLES")->to_array(null, ':1') as $table)
	{
		$result = DBManager::Query("DESC {$table}")->to_array('Field', 'Type');
		foreach ($result as $field => $dummy)
		{
			if (!preg_match('/_id_int$/', $field))
			{
				continue;
			}
			echo $table."/".$field."\n";

			DBManager::Query("ALTER TABLE `{$table}` CHANGE `{$field}` `{$field}` {$dummy} NOT NULL COMMENT 'hidden'");
		}
	}
?>