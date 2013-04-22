<?php
class Hook_RewardSystem extends Hook
{
	public static $hooks = array(
		'Device:ImageAdded'=>'ImageAddReward',
	);

	public static function ImageAddReward($youser_id)
	{
		YouserCredits::Reward($youser_id, 1.0, -1, '#<IMAGE_ADD_REWARD>');
	}
}
?>