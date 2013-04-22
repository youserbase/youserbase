<?php
class Replacer_device {
	const tag = 'device';

	private static function get_template($type = null, $size = null)
	{
		return new Template(sprintf('%s/templates/%s%s%s.php',
			dirname(__FILE__),
			self::tag,
			is_null($type) ? '' : '.'.$type,
			is_null($size) ? '' : '.'.$size
		));
	}

	public static function Prepare($matches)
	{
		$ids = array_map(create_function('$a', 'return $a["parameters"]["id"];'), $matches);
		Timer::Report('Replacer_device: before Device::Get (%u)', count($ids));
		$devices = Device::Get($ids);
		Timer::Report('Replacer_device: after Device::Get' );

		$phrases = array();
		foreach ($devices as $device)
		{
			array_push($phrases, $device['name'], $device['manufacturer']);
		}
		BabelFish::Get($phrases);

		unset($ids, $phrases, $devices);
	}

	public static function Consume($match)
	{
		$device = Device::Get($match['parameters']['id']);
		if (!$device)
		{
			return '<phrase id="UNKNOWN_DEVICE"/>';
		}

		$type = in_array('header', array_keys($match['parameters'])) ? 'header' : null;
		$size = $type ? $match['parameters'][$type] : null;

		$tab = isset($match['parameters']['tab'])
			? $match['parameters']['tab']
			: (isset($_REQUEST['tab'])
				? $_REQUEST['tab']
				: '');

		$template = self::get_template($type, $size);
		$template->assign( compact('device', 'tab') );

		if ($type=='header')
			self::FillHeaderTemplate($template, $device, $match['parameters']);
		else
			self::FillDeviceTemplate($template, $device, $match['parameters']);

		return $template->render();
	}

	private static function FillHeaderTemplate(&$template, $device, $parameters)
	{
		$picture_index = isset($_GET['picture_id'])
			? $_GET['picture_id']
			: key($device['pictures']);
		$picture_id = isset($device['pictures'][ $picture_index ])
			? $device['pictures'][ $picture_index ]
			: reset($device['pictures']);

		$device_creator = DBManager::Get('devices')->query("SELECT youser_id, UNIX_TIMESTAMP(timestamp) AS timestamp FROM device_names WHERE device_id = ?", $device['id'])->fetch_array();

		$temp_requests = device_stats::get_counts($device['id']);
		$requests = array(
			'absolute' => reset($temp_requests),
			'relative' => key($temp_requests),
		);

		$sheet = sheetConfig::get_sheet($device['id']);
		$sheet = phoneConfig::startDataSheetBuilding($sheet, 'common', $device['id']);

		$rating = reset(getratings::rating($device['id'], $template->get_variable('tab')));
		$number_of_ratings = getratings::get_number_of_ratings($device['id']);

		$main_type = DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id = ?", $device['id'])->fetch_item();

		$template->assign( compact('picture_index', 'picture_id', 'device_creator', 'requests', 'sheet', 'rating', 'number_of_ratings', 'main_type') );
		if (!empty($parameters['title']))
			$template->assign('title', $parameters['title']);
	}

	private static function FillDeviceTemplate(&$template, $device, $parameters)
	{
		$template->assign('link', isset($parameters['href'])
			? $parameters['href']
			: DeviceHelper::GetLink($device['id'], $device['manufacturer'], $device['name'], isset($parameters['tab'])?array('tab'=>$parameters['tab']):array())
		);
		$template->assign('link_class', isset($parameters['link_class']) ? $parameters['link_class'] : null);
		$template->assign('manufacturer', empty($parameters['manufacturer']) or as_boolean($parameters['manufacturer']));
		$template->assign('highlight', empty($parameters['highlight'])
			? false
			: $parameters['highlight']
		);
		$template->assign('identifier', empty($parameters['identifier'])
			? false
			: $parameters['identifier']
		);
		$template->assign('rating', !empty($parameters['rating']) and as_boolean($parameters['rating']));
		$template->assign('append', empty($parameters['append'])
			? false
			: $parameters['append']
		);
		$template->assign('append_tag', empty($parameters['append_tag'])
			? false
			: $parameters['append_tag']
		);
		$template->assign('type', empty($parameters['type'])
			? false
			: $parameters['type']
		);
		$template->assign('image', empty($parameters['type'])
			? false
			: DeviceHelper::GetImage(
				$device['id'],
				$parameters['type'],
				(isset($parameters['image']) and in_array($parameters['image'], $device['pictures']))
					? $parameters['image']
					: null
				)
		);
		$template->assign('plain', !empty($parameters['plain']) and as_boolean($parameters['plain']));
	}
}
?>