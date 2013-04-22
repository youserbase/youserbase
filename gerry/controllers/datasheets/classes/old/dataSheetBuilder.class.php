<?
class dataSheetBuilder 
{
	
	public function getData($tables)
	{
		$precondition = new preconditionData();
		$data = false;
		foreach ($tables as $table)
		{
			if($table != 'multimedia' && $table != 'gps')
			{
				$data[$table] = $precondition->getPreconditionsData($table);
			}
		}
		return $data;
	}
	
}
?>