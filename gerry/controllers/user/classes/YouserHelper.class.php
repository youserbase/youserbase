<?php
class YouserHelper
{
	public static function GetLink($youser_id, $nickname=null)
	{
		return FrontController::GetLink('User', 'Nickpage', 'Display', array('youser_id'=>$youser_id));
	}
}
?>