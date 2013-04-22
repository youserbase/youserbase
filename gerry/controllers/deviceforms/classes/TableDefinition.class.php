<?
class TableDefinition
{
	public function getTableDefinition($tables)
	{
		$definition = array();
		if(is_array($tables))
		{
			foreach ($tables as $table)
			{
				$object = new $table();
				$definition[$table] = $object->toArray();
			}
		}
		else
		{
			$object = new $tables();
			$definition = $object->toArray();
		}
		return $definition;
	}
}

?>