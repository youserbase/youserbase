<?php
class SessionException extends Exception
{
	public function __construct($message=null, $code=0)
	{
		parent::__construct('[Session] '.$message, $code);
	}
}
?>