<?php
/**
 * @version 0.8.1
 *
 */
class DBLayer_mysql extends DBLayer
{
	protected $link;

	public function __destruct()
	{
		$this->close();
	}

	public function connect()
	{
		$this->link = mysql_connect($this->host, $this->user, $this->password, true);

		if ($this->link===false)
		{
			echo mysql_error();
			throw new DBException('Could not connect to database server');
		}
	}

	public function select_database($database)
	{
		if (mysql_select_db($database, $this->link)===false)
		{
			throw new DBException('Could not connect to database');
		}
	}

	public function set_encoding($encoding)
	{
		$this->explicit_query("SET NAMES '{$encoding}'");
	}

	public function plain_query($query)
	{
		if (!empty($this->prefix))
		{
			$this->appendPrefix($query);
		}
		return $this->explicit_query($query);
	}

	public function adjust_query($query, $skip, $limit)
	{
		if ($skip===null and $limit===null)
		{
			return $query;
		}

		$query = preg_replace('/\s+LIMIT\s+\d+\s*,\s*\d+\s*$/i', '', $query);
		if ($skip===null and $limit!==null)
		{
			$skip = 0;
		}
		elseif ($skip!==null and $limit===null)
		{
			$limit = '18446744073709551615';
		}
		$query .= ' LIMIT '.(empty($skip)?'':$skip.',').$limit;

		return $query;
	}

	public function explicit_query($query)
	{
		$result = mysql_query($query, $this->link);
		$this->query_count++;

		if ($result===false)
		{
			throw new DBException(sprintf('Invalid sql `%s` (%d: %s)', $query, mysql_errno($this->link), mysql_error($this->link)));
		}

		return is_resource($result)
			? new DBLayer_mysql_Result($result)
			: mysql_affected_rows($this->link);
	}

	public function append_prefix($query)
	{
		$search = '~(^(?:SELECT .* FROM|INSERT\s(?:IGNORE\s+)?INTO|UPDATE|DELETE FROM)\s+|(?:JOIN\s+))~iS';
		$replace = '$1'.$this->prefix;
		return preg_replace($search, $replace, $query);
	}

	public function escape($argument)
	{
		if ($argument===null)
		{
			return 'NULL';
		}
		elseif (is_bool($argument))
		{
			return $argument ? '1': '0';
		}
		elseif (is_numeric($argument))
		{
			return $argument+'0.00';
		}
		elseif (is_string($argument))
		{
			return sprintf("'%s'", mysql_real_escape_string($argument, $this->link));
		}
		elseif (is_array($argument))
		{
			return implode(', ', array_map(array(&$this, 'escape'), $argument));
		}
		elseif (is_object($argument))
		{
			return $this->escape($argument->toString());
		}
		else
		{
			return $argument;
		}
	}

	public function close()
	{
		if ($this->link!==null)
		{
			mysql_close($this->link);
			$this->link = null;
		}
	}

	public function affected_rows()
	{
		return mysql_affected_rows($this->link);
	}

	public function get_inserted_id()
	{
		return mysql_insert_id($this->link);
//		return mysql_query("SELECT LAST_INSERT_ID()", $this->link);
	}
}
?>