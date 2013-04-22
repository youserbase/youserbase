<?php
class Administration_SQLDumper extends Controller
{
	public function Index()
	{
		$scopes = array();
		foreach (DBManager::GetScopes() as $scope)
		{
			$status = DBManager::Get($scope)->query("SHOW TABLE STATUS")->to_array(null, 'Rows');

			array_push($scopes, array(
				'name'=>$scope,
				'database'=>DBManager::Get($scope)->query("SELECT DATABASE()")->fetch_item(),
				'tables'=>count($status),
				'rows'=>array_sum($status)
			));
		}

		$template = $this->get_template(true);
		$template->assign('scopes', $scopes);
	}

	public function Dump()
	{
		if(ini_get('zlib.output_compression'))
		{
			ini_set('zlib.output_compression', 'Off');
		}

		Header('Pragma: public');
		Header('Expires: 0');
		Header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		Header('Cache-Control: private', false);

		Header('Content-Type: text/x-sql');
		Header('Content-Disposition: attachment; filename="youserbase_'.$_GET['scope'].'_'.date('YmdHis').'.sql";');
		Header('Content-Transfer-Encoding: text/plain');

		DBManager::SelectScope($_GET['scope']);

		$table_names = DBManager::Get()->query("SHOW TABLES")->to_array(null, ':first');
		foreach ($table_names as $table_name)
		{
			printf('DROP TABLE IF EXISTS `%s`;'."\n", $table_name);

			$result = DBManager::Get()->query("SHOW CREATE TABLE ".$table_name);
			$row = $result->fetch_array();
			$result->release();
			print end($row).";\n";

			$columns = DBManager::Get()->query("DESC ".$table_name)->to_array(null, 'Field');

			$result = DBManager::Get()->query("SELECT `".implode("`,`", $columns)."` FROM `{$table_name}`");
			while ($row = $result->fetch_array())
			{
				$data = array();
				foreach ($columns as $column)
				{
					array_push($data, $row[$column]);
				}
				printf("INSERT INTO `%s` (`%s`) VALUES (%s);\n",
					$table_name,
					implode("`,`", $columns),
					implode(",", array_map(array(DBManager::Get(), 'escape'), $data))
				);
			}
			$result->release();
		}
		die;
	}
}