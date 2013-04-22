<?php
class formBuilder
{
	
	public function hiddenField()
	{
	
	}
	
	public function textField($fieldName, $value=null, $required=null, $pattern=null)
	{
		$field = "<input type='text' name='$fieldName' id='$fieldName'";
		if(!empty($value))
		{
			$field .= " value='$value'";
		}
		if(!empty($required))
		{
			$field .= " required='$required'";
		}
		if(!empty($pattern))
		{
			$field.= " pattern='$pattern'";
		}
		$field .= "/>";
		return $field;
	}
	
	public function selectField()
	{
		
	}
	
	function hierselect()
	{
		
	}
}
?>