<?php
class Activity_Logger extends Hook
{
	public static $hooks = array(
		'Config:Options'=>'GetOptions',
		'Garbage:Collect'=>'GarbageCollect',
		'Youser:PinboardEntry'=>'Pinboard',
		'Youser:FriendAdd'=>'FriendAdd',
		'Device:Edited'=>'DeviceEdit',
		'Device:Created'=>'DeviceCreate',
		'Device:ImageAdded'=>'DeviceImageAdd',
		'Youser:WroteComment' => 'CommentWritten',
		'Youser:EditComment' => 'CommentEdited',
		'Youser:EditCompareComment' => 'CompareCommentEdited',
		'Youser:OwnsDevice' => 'DeviceOwner',
		'Youser:NoLongerOwnsDevice' => 'DeviceOwnerNoMore',
		'Youser:love' => 'YouserLikesDevice',
		'Youser:hate' => 'YouserHatesDevice',
		'Youser:DeletedOpinion' => 'YouserDeletedOpinion'
	);

	public static function GetOptions()
	{
		return array(
			'activity:garbagedays:range:1,28',
		);
	}

	public static function GarbageCollect()
	{
		Activity::CollectGarbage(Config::Get('activity:garbagedays')*24*60);
	}

	public static function Pinboard($youser_id, $pinboard_youser_id)
	{
		Activity::Log('PinboardEntry', $youser_id, $pinboard_youser_id);
	}

	public static function FriendAdd($youser_id, $friend_id)
	{
		Activity::Log('FriendAdd', $youser_id, $friend_id);
	}

	public static function DeviceEdit($youser_id, $device_id)
	{
		if (empty($device_id))
		{
			return;
		}
		Activity::Clear('DeviceEdit', $youser_id, $device_id);
		Activity::Log('DeviceEdit', $youser_id, $device_id);
	}

	public static function DeviceCreate($youser_id, $device_id)
	{
		if (empty($device_id))
		{
			return;
		}
		Activity::Log('DeviceCreate', $youser_id, $device_id);
	}

	public static function DeviceImageAdd($youser_id, $device_id, $image_id=null)
	{
		Activity::Log('DeviceImageAdd', $youser_id, $device_id, $image_id);
	}
	
	public static function CommentWritten($youser_id, $device_id)
	{
		Activity::Log('CommentWritten', $youser_id, $device_id);
	}
	
	public static function CommentEdited($youser_id, $device_id)
	{
		Activity::Log('CommentWritten', $youser_id, $device_id);
	}
	
	public static function CompareCommentEdited($youser_id, $device_id)
	{
		Activity::Log('CompareCommentWritten', $youser_id, $device_id);
	}
	
	public static function DeviceOwner($youser_id, $device_id)
	{
		Activity::Log('DeviceOwner', $youser_id, $device_id);
	}
	
	public static function DeviceOwnerNoMore($youser_id, $device_id)
	{
		Activity::Log('DeviceOwnerNoMore', $youser_id, $device_id);
	}
	
	public static function YouserLikesDevice($youser_id, $device_id)
	{
		Activity::Log('YouserLikesDevice', $youser_id, $device_id);
	}
	
	public static function YouserHatesDevice($youser_id, $device_id)
	{
		Activity::Log('YouserHatesDevice', $youser_id, $device_id);
	}
	
	public static function YouserDeletedOpinion($youser_id, $device_id)
	{
		Activity::Log('YouserDeletedOpinion', $youser_id, $device_id);
	}
	
}
?>