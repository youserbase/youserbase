<?php
class Activity_Display extends Hook
{
	public static $hooks = array(
		'Plugin:Activity'=>'ActivityDisplay',
		'Config:Options'=>'GetOptions'
	);

	public static function GetOptions()
	{
		return array(
			'activity:pagination_count:range:5,30,5'
		);
	}

	private static $actitivies_count = 0;
	private static function GetActivitiesReset()
	{
		self::$actitivies_count = 0;
	}

	private static function GetActivities(/*$youser_id*/)
	{
		$temp_activities = Activity::Retrieve(Config::Get('activity:pagination_count'), self::$actitivies_count);
		self::$actitivies_count += Config::Get('activity:pagination_count');
		return empty($temp_activities)
			? false
			: $temp_activities;
	}

	public static function ActivityDisplay($youser_id)
	{
		Session::Set('activity', 'timestamp', time());

		$activities = array();
		while (count($activities) < Config::Get('activity:pagination_count'))
		{
			if (empty($temp_activities))
			{
				$temp_activities = self::GetActivities(/*$youser_id*/);
			}
			if ($temp_activities === false)
			{
				break;
			}
			$activity = array_shift($temp_activities);

			if ($last_activity = end($activities))
			{
				if ($activity['activity']==$last_activity['activity'] and $activity['subject_id']==$last_activity['subject_id'])
				{
					array_pop($activities);
					if (!is_array($last_activity['scope']))
					{
						$last_activity['scope'] = array($last_activity['scope']);
					}
					array_push($last_activity['scope'], $activity['scope']);
					$activity = $last_activity;
				}
			}

			array_push($activities, $activity);
		}

		$template = self::get_template('activity_display');
		$template->assign('activities', $activities);
		return $template->fetch();
	}
}
?>