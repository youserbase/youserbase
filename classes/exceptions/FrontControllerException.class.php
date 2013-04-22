<?php
class FrontControllerException extends Exception
{
	private $location;

	public function __construct($message=null, $code=0, $location=null)
	{
		$this->location = $location;
		parent::__construct('[FrontController] '.$message, $code);
	}

	public function get_location()
	{
		return $this->location;
	}
}
?>