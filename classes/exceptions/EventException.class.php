<?php
/**
 * Specialized exception for all "Event" exceptions
 *
 */
class EventException extends Exception
{
	/**
	 * Constructor, adapted to add the calling class
	 *
	 * @param String $message
	 * @param Number $code
	 */
	public function __construct($message=null, $code=0)
	{
		parent::__construct('[Event] '.$message, $code);
	}
}
?>