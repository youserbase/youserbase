<?php
abstract class DBLayer_Result
{
	abstract public function __construct($resource);
	abstract public function num_rows();
	abstract public function is_empty();
	abstract public function seek($position);
	abstract public function fetch_array();
	abstract public function release();

/**** Utility functions *****/

	public function is_present()
	{
		if ($this->is_empty())
		{
			$this->release();
			return false;
		}
		$this->release();
		return true;
	}

	/**
	 * Fetches a single field from the resultset
	 *
	 * @param mixed $field Optional, field to fetch, null for first field
	 * @return mixed
	 */
	public function fetch_item($field=null)
	{
		if ($this->is_empty())
		{
			return null;
		}

		$row = $this->fetch_array();
		if (is_array($field))
		{
			if (empty($field))
			{
				$item = $row;
			}
			else
			{
				$item = array();
				foreach ($field as $index)
				{
					$item[$index] = $row[$index];
				}
			}
		}
		else
		{
			$item = $field===null
				? reset($row)
				: $row[$field];
		}
		$this->release();

		return $item;
	}

	/**
	 * Converts the resultset into an array
	 *
	 * @param string $index_field Optional, index column to use as key for the array
	 * @param mixed $fields Optional, fields to grab from resultset
	 * @return array
	 */
	public function to_array($index_field = null, $fields = null, $sort = null)
	{
		if ($this->is_empty())
		{
			$this->release();
			return array();
		}

		$result = array();
		while ($row = $this->fetch_array())
		{
			$data = $row;
			if ($fields!==null and is_string($fields))
			{
				if (array_key_exists($fields, $data))
				{
					$data = $data[$fields];
				}
				elseif ($fields==':first')
				{
					$data = reset($data);
				}
				elseif (preg_match('/^:(\d)$/', $fields, $match))
				{
					$data = pick($data, $match[1]-1);
				}
				else
				{
					printf('%s - Unknown field "%s" specified'."\n",
						__METHOD__,
						$fields
					);
//					debug_print_backtrace();
				}
			}
			elseif ($fields !== null and is_array($fields) and count(array_diff($fields, array_keys($data))) == 0)
			{
				foreach (array_diff(array_keys($data), $fields) as $field)
				{
					unset($data[$field]);
				}
			}
			elseif ($fields!==null and is_array($fields))
			{
				$temp = array();
				foreach ($fields as $field)
				{
					if (array_key_exists($field, $data))
					{
						$temp[$field] = $data[$field];
					}
					elseif ($field==':first')
					{
						$temp[pick(array_keys($data), 0)] = pick($data, 0);
					}
					elseif (preg_match('/^:(\d)$/', $field, $match))
					{
						$temp[pick(array_keys($data), $match[1])] = pick($data, $match[1]);
					}
					else
					{
						printf('%s - Unknown field "%s" specified'."\n",
							__METHOD__,
							$field
						);
	//					debug_print_backtrace();
					}
				}
			}

			if (is_array($index_field))
			{
				foreach ($index_field as $temp)
				{
					if (!empty($row[$temp]))
					{
						$result[$row[$temp]] = $data;
					}
				}
			}
			elseif ($index_field === null or !isset($row[$index_field]))
			{
				array_push($result, $data);
			}
			else
			{
				$result[$row[$index_field]] = $data;
			}
		}
		$this->release();

		if ($sort !== null)
		{
			// Sort mechanisms
		}

		return $result;
	}
}
?>