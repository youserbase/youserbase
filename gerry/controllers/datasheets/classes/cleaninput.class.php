<?php
class cleaninput
{
	/**
	*validates the inputdata
	*
	*/
	public static function clean($tablename, $table_data)
	{
		$object = new $tablename;
		$answer = array();
		foreach($table_data as $name => $value)
		{
			if(!empty($value))
			{
				$type = self::decide_type($object->$name['type']);
				if($type === 'int')
				{
					if(!is_numeric($value))
					{
						$answer[$name] = 'Fehler';
					}
					else
					{
						$answer[$name] = self::cleanvalue($value);
					}
				}		
				else if ($type === 'string')
				{
					if(!is_string($value))
					{
						$answer[$name] = false;
					}
					else
					{
						$answer[$name] = self::cleanvalue($value);
					}
				}
			}
		}
		return $answer;
	}
	
	private static function decide_type($type)
	{
		if(strpos('char', $type)=== true)
		{
			return 'string';
		}
		else if(strpos('int', $type) === true)
		{
			return 'int';
		}
		return false;
	}
	
	
	private static function cleanvalue($value)
	{
		$clean = strip_tags($value);
		$clean = htmlspecialchars($clean);
		while(strpos($clean, '..') !== false)
		{
			$clean = str_replace('..', '.', $clean);
		}
		return $clean;
	}
}
?>