<?php
abstract class DBLayer
{
	public $query_count = array(
		'SELECT'=>0,
		'UPDATE'=>0,
		'DELETE'=>0
	);
	protected $log_queries = false;
	protected $debug = false;
	public $queries = array();

	protected $limit = null;
	protected $skip = null;
	protected $prefix = '';

	protected $host;
	protected $user;
	protected $password;

	abstract public function connect();
	abstract public function select_database($database);
	abstract public function set_encoding($encoding);
	abstract public function adjust_query($query, $skip, $limit);
	abstract public function explicit_query($query);
	abstract public function escape($argument);
	abstract public function append_prefix($query);
	abstract public function close();
	abstract public function affected_rows();
	abstract public function get_inserted_id();

	public function __construct($host, $user, $password)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;

		$this->connect();
	}

	public function log_queries($state = true)
	{
		$this->log_queries = $state;
	}

	public function set_prefix($prefix)
	{
		$this->prefix = $prefix;
	}

	public function skip($amount)
	{
		$this->skip = $amount;
		return $this;
	}

	public function limit($amount)
	{
		$this->limit = $amount;
		return $this;
	}

	public function debug($duration=1)
	{
		if (is_bool($duration))
		{
			$this->debug = $duration;
		}
		else
		{
			$this->debug = is_bool($this->debug)
				? $duration
				: $this->debug + $duration;
		}
		return $this;
	}

	public function query($query)
	{
		$arguments = array_slice(func_get_args(), 1);

		if ($this->log_queries)
		{
			$this->query_count['SELECT'] += $updated_select=(strpos($query, 'SELECT ')===0);
			$this->query_count['DELETE'] += $updated_delete=(strpos($query, 'DELETE ')===0);
			if ($updated_select + $updated_delete == 0)
			{
				$this->query_count['UPDATE'] += 1;
			}
		}

		$arguments = array_map(array(&$this, 'escape'), $arguments);

		$query_parts = explode('?', $query);
		$query = array_shift($query_parts);

		if (count($arguments)<count($query_parts))
		{
			throw new DBException('Unbalanced quantity of placeholders and arguments in query');
		}

		while (!empty($query_parts))
		{
			$value = array_shift($arguments);
			if ($value===$this->escape(null) and strpos($query, ' WHERE ')>0)
			{
				$query = preg_replace('/\s*=\s*$/', ' IS ', $query);
			}
			$query .= $value.array_shift($query_parts);
		}

		if (!empty($this->prefix))
		{
			$this->append_prefix($query);
		}

		$query = $this->adjust_query($query, $this->skip, $this->limit);
		$this->skip = null;
		$this->limit = null;

		$start_time = microtime(true);

		$result = $this->explicit_query($query);

		if ($this->log_queries)
		{
			array_push($this->queries, array(
				'query'=>$query,
				'duration'=>microtime(true)-$start_time,
				'rows'=>is_object($result)
					? $result->num_rows()
					: $result
			));
		}

		if ($this->debug)
		{
			echo $query."\n".print_r($result, true)."\n\n";
			if (!is_bool($this->debug))
			{
				$this->debug -= 1;
			}
		}

		return $result;
	}
}
?>