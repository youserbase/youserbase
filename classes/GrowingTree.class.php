<?php
// TODO: Bislang nur 2-stufig, n-stufig wird gebraucht
class GrowingTree
{
	private $branches = array();

	public function feed($id, $data, $parent, $previous)
	{
		if (!isset($this->branches[$parent]))
		{
			$this->branches[$parent] = array();
		}
		$this->branches[$parent][$previous] = array(
			'id'=>$id,
			'data'=>$data
		);
	}

	public function grow()
	{
		$branches = array();
		foreach ($this->branches as $index=>$branch)
		{
			$branches[$index] = array();
			$previous = null;

			while (isset($branch[$previous]))
			{
				$id = $branch[$previous]['id'];
				$branches[$index][$id] = $branch[$previous]['data'];
				unset($branch[$previous]);
				$previous = $id;
			}
		}

		$this->branches = $branches;

		return $this;
	}

	public function chop()
	{
		$branches = $this->branches;
		while (count($branches)>1)
		{
			$item = end($branches);
			$previous = key($branches);

			while ($branch=prev($branches))
			{
				if (isset($branch[$previous]))
				{
					$branches[key($branches)][$previous]['children'] = $item;
					break;
				}
			}
			array_pop($branches);
		}

		if (empty($branches))
		{
			return array();
		}

		$result = array();
		foreach (array_pop($branches) as $branch)
		{
			if (isset($branch['children']))
			{
				$branch['children'] = array_values($branch['children']);
			}
			array_push($result, $branch);
		}
		//echo '<pre>',print_r($result, true),'</pre>';
		return $result;
	}
}
?>