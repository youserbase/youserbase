<?php
class ControllerException extends Exception
{
	public function __construct($message=null, $code=0)
	{
		parent::__construct('[Controller] '.$message, $code);
	}
}
?>