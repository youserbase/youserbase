<?php
class DBException extends Exception
{
	public function __construct($message=null, $code=0)
	{
		parent::__construct('[DataBase] '.$message, $code);
	}
}
?>