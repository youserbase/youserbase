<?php
class Querybuilder
{
	public function build_search_query($string)
	{
		$search = explode(' ', $string);
		if(is_array($search) && !empty($search))
		{
			$and_search = array();
			$or_search = array();
			foreach ($search as $needle)
			{
				$tmp = BabelFish::ReverseLookup($needle, '', true);
				if(count($tmp) > 2)
				{
					foreach ($tmp as $line)
					{
						$tmp2[$line['phrase_id']] = $line['phrase_id'];
					}
					$or_search[] = $tmp2;
					unset($tmp2);
				}
				else if(!empty($tmp))
				{
					$and_search[] = reset($tmp);
				}
				else $or_search[] = array($needle);
			}
			$query = '';
			if(!empty($and_search))
			{
				$query .= '(';
				foreach ($and_search as $key => $search)
				{
					if($key >= 1)
					{
						$query .= ' &';
					}
					$query .= ' *';
					$query .= implode('* & *', $search);
					$query .='*';
				}
				$query .= ')';
			}
			if(!empty($or_search))
			{
				if(!empty($and_search))
				{
					$query .= ' & ';
				}
				foreach ($or_search as $key => $search)
				{
					if($key >= 1)
					{
						$query .= ' & ';
					}
					$query .= ' (*';
					$query .= 	implode('* | *', $search);
					$query .='*)';
				}
			}
		}
		return $query;
	}
}
?>