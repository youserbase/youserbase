<?php
class Dispatcher
{
	private static function MapDeviceIdToManufacturerAndModel(&$parameters)
	{
		if (empty($parameters['device_id']) and isset($_REQUEST['device_id']))
			$parameters['device_id'] = $_REQUEST['device_id'];
		if (empty($parameters['device_id']))
			return false;

		if (is_null($mapped = MemoryShelf::Pick('id_to_device')->get($parameters['device_id'])))
		{
			if (!($device = Device::Get($parameters['device_id'])))
				return false;
			$mapped = array(
				'manufacturer' => urlencode(str_replace(' ', '_', BabelFish::Get(is_array($device['manufacturer'])?reset($device['manufacturer']):$device['manufacturer']))),
				'model' => urlencode(str_replace(' ', '_', BabelFish::Get(is_array($device['name'])?reset($device['name']):$device['name']))),
			);
			MemoryShelf::Pick('id_to_device')->put($parameters['device_id'], $mapped);
		}
		unset($parameters['device_id']);
		return $mapped;
	}

	public static function MapManufacturerAndModelToDeviceId(&$parameters)
	{
		if (empty($parameters['manufacturer']) and isset($_REQUEST['manufacturer']))
			$parameters['manufacturer'] = $_REQUEST['manufacturer'];
		if (empty($parameters['model']) and isset($_REQUEST['model']))
			$parameters['model'] = $_REQUEST['model'];
		$manufacturer = str_replace('_', ' ', urldecode($parameters['manufacturer']));
		$model = str_replace('_', ' ', urldecode($parameters['model']));

		$device_id = Device::Lookup($manufacturer, $model);
		if (!$device_id)
			return false;

		unset($parameters['manufacturer'], $parameters['model']);
		return compact('device_id');
	}

	private static function MapManufacturerIdToManufacturer(&$parameters)
	{
		if (empty($parameters['manufacturer_id']) and isset($_REQUEST['manufacturer_id']))
			$parameters['manufacturer_id'] = $_REQUEST['manufacturer_id'];

//		if (is_null($mapped = self::Retrieve('id_to_manufacturer', $parameters['manufacturer_id'])))
		{
			$manufacturer = Manufacturer_Name::Get($parameters['manufacturer_id']);
			if (!$manufacturer or !isset($manufacturer['name']) or !($name = BabelFish::Get($manufacturer['name'])))
				return false;
			$mapped = array(
				'manufacturer' => urlencode(str_replace(' ', '_', $name)),
			);
		}
		unset($parameters['manufacturer_id']);
		return $mapped;
	}

	private static function MapManufacturerToManufacturerId(&$parameters)
	{
		if (empty($parameters['manufacturer']) and isset($_REQUEST['manufacturer']))
			$parameters['manufacturer'] = $_REQUEST['manufacturer'];

//		if (is_null($mapped = self::Retrieve('manufacturer_to_id', $parameters['manufacturer_'])))
		{
			$id = Manufacturer_Name::LookUp(str_replace('_', ' ', urldecode($parameters['manufacturer'])));
			if (!$id)
				return false;
			$mapped = array(
				'manufacturer_id' => $id
			);
		}
		unset($parameters['manufacturer']);
		return $mapped;
	}

	public static function MapYouserIdToNickname(&$parameters)
	{
		if (empty($parameters['youser_id']) and isset($_REQUEST['youser_id']))
			$parameters['youser_id'] = $_REQUEST['youser_id'];
		if (empty($parameters['youser_id']))
			return false;

//		if (is_null($mapped = self::Retrieve('id_to_youser', $parameters['youser_id'])))
		{
			$youser = Youser::Get($parameters['youser_id']);
			if (!$youser)
				return false;
			$mapped = array('nickname'=>$youser->nickname);
		}
		unset($parameters['youser_id']);
		return $mapped;
	}

	public static function MapNicknameToYouserId(&$parameters)
	{
		if (empty($parameters['nickname']) and isset($_REQUEST['nickname']))
			$parameters['nickname'] = $_REQUEST['nickname'];
		if (empty($parameters['nickname']))
			return false;

//		if (is_null($mapped = self::Retrieve('youser_to_id', $parameters['nickname'])))
		{
			$youser = Youser::Get($parameters['nickname'], true);
			if (!$youser)
				return false;
			$mapped = array('youser_id'=>$youser->id);
		}
		unset($parameters['nickname']);
		return $mapped;
	}

	public static $mapping = array(
		'Mobile/#{manufacturer}/#{model}/comments.rss' => array(
			'location' => 'Datasheets/Export/Comments?type=rss&device_id=#{device_id}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Mobile/#{manufacturer}/#{model}/comments.atom' => array(
			'location' => 'Datasheets/Export/Comments?type=atom&device_id=#{device_id}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Mobile/#{manufacturer}/#{model}/Images' => array(
			'location' => 'Devices/ImageGallery/Index?device_id=#{device_id}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Mobile/#{manufacturer}/#{model}/Media/#{media_id}' => array(
			'location' => 'Datasheets/datasheets/page?device_id=#{device_id}&tab=Media&media_id=#{media_id}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Mobile/#{manufacturer}/#{model}/#{tab}' => array(
			'location' => 'Datasheets/datasheets/page?device_id=#{device_id}&tab=#{tab}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Mobile/#{manufacturer}/#{model}' => array(
			'location' => 'Datasheets/datasheets/page?device_id=#{device_id}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Mobile/Create' => 'Datasheets/datasheets/buildStartSheet',
		'Device/#{manufacturer}/#{model}/#{tab}' => array(
			'location' => 'Datasheets/datasheets/page?device_id=#{device_id}&tab=#{tab}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Device/#{manufacturer}/#{model}' => array(
			'location' => 'Datasheets/datasheets/page?device_id=#{device_id}',
			'input' => 'MapManufacturerAndModelToDeviceId',
			'output' => 'MapDeviceIdToManufacturerAndModel',
		),
		'Plugin/#{plugin_name}/Config' => 'Administration/Plugins/Configuration?plugin_name=#{plugin_name}',
		'Plugin/#{plugin}/#{action}' => 'System/Plugins/Call?plugin=#{plugin}&action=#{action}',
		'Catalogue/#{manufacturer}' => array(
			'location' => 'Datasheets/Catalogue/Manufacturer?manufacturer_id=#{manufacturer_id}',
			'input' => 'MapManufacturerToManufacturerId',
			'output' => 'MapManufacturerIdToManufacturer',
		),
		'Catalogue' => 'Datasheets/Catalogue/Index',
		'user/youserbase' => 'System/System/Index',
		'Youser/youserbase' => 'System/Pages/Display?page=ABOUT',
		'Youser/#{nickname}/Pinboard' => array(
			'location' => 'User/Nickpage/Pinboard?youser_id=#{youser_id}',
			'input' => 'MapNicknameToYouserId',
			'output' => 'MapYouserIdToNickname',
		),
		'Youser/#{nickname}' => array(
			'location' => 'User/Nickpage/Display?youser_id=#{youser_id}',
			'input' => 'MapNicknameToYouserId',
			'output' => 'MapYouserIdToNickname',
		),
		'Search' => 'System/Search/Search',
		'Nickpage' => 'User/Nickpage/Display',
		'Maintenance' => 'System/System/Maintenance',
		'Messages/Read/#{message_id}' => 'User/Messages/Read?message_id=#{message_id}',
//		'Messages/Outbox/#{page}' => 'User/Messages/Outbox?page=#{page}',
		'Messages/Outbox' => 'User/Messages/Outbox',
		'Messages' => 'User/Messages/Index',
		'Profile' => 'User/Profile/Modify',
		'Settings' => 'User/Profile/Settings',
		'PasswordChange' => 'User/Profile/PasswordChange',
		'Poll' => 'User/AJAX/Poll',
//		'Translate/#{nonce}' => 'System/BabelFish/Translate?nonce=#{nonce}',
		'News/Administration/Insert' => 'News/Administration/Insert',
		'News/Administration' => 'News/Administration/Index',
		'News/#{news_id}' => 'News/News/Read?news_id=#{news_id}',
		'Tags' => 'Devices/Tags/Index',
		'Tag/#{tag}' => 'Devices/Tags/Tag?tag=#{tag}',
		'Confirm/#{key}' => 'System/System/Confirm?key=#{key}',
		'ForgotPassword' => 'User/Access/PasswordReminder',
		'Login' => 'User/Access/Login',
		'Logout' => 'User/Access/Logout',
		'Registered' => 'User/Access/Register?Registered',
		'Register' => 'User/Access/Register',
		'Nickpage' => 'User/Nickpage/Display',
		'About' => 'System/Pages/Display?page=ABOUT',
		'Masthead' => 'System/Pages/Display?page=MASTHEAD',
		'Contact' => 'System/Pages/Display?page=CONTACT',
		'PrivacyPolicy' => 'System/Pages/Display?page=PRIVACY_POLICY',
		'Advertising' => 'System/Pages/Display?page=ADVERTISING',
		'TermsOfService' => 'System/Pages/Display?page=TOS',
		'FAQ' => 'System/Pages/Display?page=FAQ',
		'Help' => 'System/Pages/Display?page=HELP',
		'Donated' => 'System/Paypal/DonationThanks',
		'Consultant' => 'Devices/Consultant/Index',
		'Device/#{device_id}/photos\.rss' => 'Devices/ImageGallery/Cooliris?device_id=#{device_id}',
#		'Tag/#{tag}' => 'System/Tags/Index?tag=#{tag}',
		'Community' => 'User/Profile/Index',
		'$' => 'System/System/Index',
		'#{module}/#{controller}/#{method}' => '#{module}/#{controller}/#{method}',
	);

	/**
	 * Tries to map a given location to a aliased uri
	 *
	 * @param array $location
	 * @return mixed Mapped uri or false on failure
	 */
	public static function Map(&$location)
	{
		$stored_location = $location;
		$result = false;

		foreach (self::$mapping as $needle => $mapping)
		{
			$_mapping = is_array($mapping)
				? $mapping['location']
				: $mapping;

			if ($_mapping{0}!=$location['location']['module']{0} or (strpos($_mapping, $location['location']['module'].'/'.$location['location']['controller'].'/'.$location['location']['method'])!==0))
				continue;

			$temp_mapping = Template::Interpolate($_mapping, isset($location['parameters']) ? $location['parameters'] : array());
			$temp_mapping = Location::Parse($temp_mapping);

			$equal = true;
			foreach ($temp_mapping['parameters'] as $index => $value)
				if (strpos($value, '#{') === false)
					$equal = ($equal and (!isset($location['parameters'][$index]) or $location['parameters'][$index]==$value));

			if (!$equal)
				continue;

			if (is_array($mapping) and isset($mapping['output']))
			{
				$temp_parameters = call_user_func(array('self', $mapping['output']), &$location['parameters']);
				if ($temp_parameters === false)
				{
					unset($temp_parameters);
					continue;
				}
			}

			foreach ($temp_mapping['parameters'] as $index => $value)
				unset($location['parameters'][$index]);

			$parameters = array_merge($location['parameters'], $temp_mapping['parameters']);
			if (isset($temp_parameters))
				$parameters = array_merge($parameters, $temp_parameters);

			$needle = str_replace('$', '', $needle);
			$temp_result = empty($parameters)
				? $needle
				: Template::Interpolate($needle, $parameters);
			if (strpos($temp_result, '#{'))
			{
				$location = $stored_location;
				unset($temp_parameters);
				continue;
			}
			$result = $temp_result;
			break;
		}
		return $result;
	}

	public static function Resolve($uri)
	{
		$uri = rtrim($uri, '/');

		$mapped_parameters = array();
		$mapped = false;

		foreach (self::$mapping as $needle => $mapping)
		{
			$needle = str_replace('/', '\\/', $needle);
			$needle = preg_replace('/#\{(.*?)\}/', '(?P<$1>[^\/?&]+)', $needle);
			if (!preg_match_all('/^(?:(?P<preferred_language>[a-z]{2})(?:\/|$))?'.$needle.'/i', $uri, $matches, PREG_SET_ORDER))
				continue;
			$parameters = array();
			foreach ($matches[0] as $index => &$match)
				if (!is_numeric($index))
					$parameters[$index] = $match;

			$url_parts = parse_url($uri);
			parse_str(isset($url_parts['query']) ? $url_parts['query'] : '', $more_parameters);
			$mapped_parameters = array_merge($parameters, $more_parameters);
			if (is_array($mapping) and isset($mapping['input']))
			{
				$temp_parameters = call_user_func(array('self', $mapping['input']), $parameters);
				if ($temp_parameters === false)
					continue;
				$mapped_parameters = array_merge($mapped_parameters, $temp_parameters);
			}
			$mapped_parameters['preferred_language'] = isset($mapped_parameters['preferred_language']) ? $mapped_parameters['preferred_language'] : BabelFish::GetLanguage();
			$mapped = Template::Interpolate(is_array($mapping)?$mapping['location']:$mapping, $mapped_parameters);
			break;
		}

		if ($mapped)
			$location = Location::Parse($mapped);
		else
		{
			$location = Location::Parse('System/System/Page_404?resource='.urlencode($uri));
			if (preg_match('/^([a-z]{2})\//i', $uri, $matches))
				$mapped_parameters['preferred_language'] = $matches[1];
		}

		$location = $mapped
			? Location::Parse($mapped)
			: Location::Parse('System/System/Page_404?resource='.urlencode($uri));

		if (!FrontController::CheckLocation($location))
			$location = Location::Parse('System/System/Page_404?resource='.urlencode($uri));

		foreach (array_merge($mapped_parameters, $location['parameters']) as $key => $value)
			FrontController::PopulateRequest($key, $value);

		FrontController::PopulateRequest('module', $location['module']);
		FrontController::PopulateRequest('controller', $location['controller']);
		FrontController::PopulateRequest('method', $location['method']);

		return $location;
	}
}
?>