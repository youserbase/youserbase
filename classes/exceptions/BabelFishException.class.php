<?php
class BabelFishException extends Exception
{
	public function __construct($message=null, $code=0)
	{
		parent::__construct('[BabelFish] '.$message, $code);
	}
}
?>