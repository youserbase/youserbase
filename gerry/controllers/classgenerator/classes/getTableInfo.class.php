<?php
class getTableInfo
{
	public function describeTable($table)
	{
		$query = "SHOW FULL COLUMNS FROM `$table;";
		$tables = DBManager::Get('devices')->query($query)->to_array();
		return $tables;
	}
	
	public function showTables()
	{
		$query = "SHOW TABLES;";
		$tables = DBManager::Get('devices')->query($query)->to_array();
		return $tables;
	}
}
?>