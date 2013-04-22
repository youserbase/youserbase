<?php
class Hook_Administration extends Hook
{
	public static $hooks = array(
		'Config:Options'=>'GetOptions'
	);

	public static function GetOptions()
	{
		return array(
			'phrases:pagination_count:range:5,30,5',
		);
	}

}
?>