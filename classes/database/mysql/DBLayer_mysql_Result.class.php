<?php
class DBLayer_mysql_Result extends DBLayer_Result
{
	protected $resource;
	protected $debug;

	public function __construct($resource, $debug = false)
	{
		if ($resource===false)
		{
			throw new DBException('Invalid sql result');
		}
		$this->resource = $resource;

		$this->debug = $debug;
	}

	public function num_rows()
	{
		return is_resource($this->resource)
			? mysql_num_rows($this->resource)
			: false;
	}

	public function is_empty()
	{
		return !is_resource($this->resource) or mysql_num_rows($this->resource)===0;
	}

	// Deprecated
	public function isEmpty()
	{
		return $this->is_empty();
	}

	public function seek($position)
	{
		mysql_data_seek($this->resource, $position);
	}

	public function fetch_array($mode = MYSQL_ASSOC)
	{
		return mysql_fetch_array($this->resource, $mode);
	}

	// Deprecated
	public function fetchArray($mode = MYSQL_ASSOC)
	{
		return $this->fetch_array($mode);
	}

	/**
	 * Releases the resultset
	 */
	public function release()
	{
		mysql_free_result($this->resource);
	}
}
?>