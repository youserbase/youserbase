<?php
class sheetConfig
{
	public static $mobilephone_sheet = array(
		'common' => array(
			'indication' => array(
				'mobilephone',
				'operatingsystem'),
			'market_information',
			'internal_memory',
			'extendable_memory',
			'ram',
			'display' => array(
				'component_id',
				'display_size_diagonally',
				'display_resolution_x',
				'display_resolution_y',
				'color_space_type_id',
				'display_type_id'
			),
			'processor',
			'body' => array(
				'body',
				'body_specials',
				'color',
				'material'
			),
			'battery',
			'camera' => array(
				'component_id',
				'component_id_int',
				'camera_max_resolution'
			),
			'video_camera' => array(
				'component_id',
				'component_id_int',
				'camera_max_resolution'
			),
			'gps_chip',
			'resistance',
			'accessories',
			'radiation',
		),
		'technology' => array(
			'secondary_display',
			'input_method',
			'internal_memory',
			'ports' =>  array(
				'data_port',
				'audio_port'
			),
			'wireless' => array(
 				'wifi_networks',
 				'bluetooth_networks',
 				'bluetooth_networks_protocoll'
 			),
			'gsm_runtime',
			'umts_runtime',
			'battery_runtime_audio',
			'battery_runtime_video',
 			'antenna',
 			'additional_hardware',
 		),
		'communication' => array(
			'phone' => array(
				'gsm_networks',
				'cdma_networks',
				'umts_networks',
				'hspa_networks'
			),
			'data_port',
				'wireless' => array(
					'wifi_networks',
					'bluetooth_networks',
					'bluetooth_networks_protocoll',
			),
				
			'call_notification',
			'ringtone_format',
			'mobilephone_profiles',
			'personalization' => array(
				'themes',
				'ringtone_format',
				'background_picture',
				'screensaver',
				'profiles'
			),
		),
		'multimedia' => array(
			'browser' => 'browser',
			'audio_player' => array(
				'audio_player_functions',
				'audio_player_specials',
				'music_format',
				'playlist_functions',
			),
			'audio_recording' => array(
				'audio_recording_formats',
				'audio_recording_functions'
			),
			'radio_tuner' => array(
				'radio_frequency',
				'radio_tuner'
			),
			'fm_transmitter',
			'camera' => array(
				'camera',
				'camera_chip',
				'finder',
				'focus',
				'zoom'
			),
			'picture_enhancents' => array(
				'brightness_controll',
				'white_balance',
				'image_stabilizer',
				'scene_mode',
				'camera_programs'
			),
			'video_recording' => array(
				'video_camera',
				'video_scene_mode',
				'white_balance',
				'microphone'
			),
			'video_playback' => array(
				'video_player_functions'
			),
			'flash' => array(
				'flash',
				'flash_functions'
			),
			'picture_display' => array(
				'picture_format'
			),
			'picture_editing',
			'ports' => array(
				'data_port',
				'audio_port'
			),
		),
		'navigation' => array(
			'gps' => array(
				'gps',
				'gps_chip'
			),
			'gps_software',
			'gps_function',
			'nav_routing' => array(
				'nav_routing',
				'nav_routing_avoid'
			),
			'nav_output' => array(
				'nav_voice_output',
				'nav_display_output',
				'nav_compass'
			),
		),
		'messaging' => array(
			'sms',
			'mms' => array(
				'mms',
				'mms_data'
			),
			'email' => array(
				'email',
				'email_functions',
				'email_protocoll'
			),
			'instant_messenger'
		),
		'organization' => array(
			'contacts' => array(
				'contacts',
				'contacts_functions',
				'businesscard',
				'businesscard_sendvia'
			),
			'calendar' => array(
				'calendars',
				'calendars_functions',
				'alarm_functions'
			),
			'notes' => array(
				'notes_functions',
				'notes'
			),
			'time_functions',
			'calculator' => array(
				'calculator',
				'calculator_functions'
			),
			'document_read',
			'document_edit',
			'phone_modem' => array(
				'phone_modem',
				'modem_wireless_connections',
				'modem_wired_connections'
			),
			/*'personalization' => array(
				'themes',
				'ringtone_format',
				'background_picture',
				'screensaver',
				'profiles',
			),*/
		),
		'Comments' => array(
			'comments' => 'comments'
		),
		'Media' => array(
			'media' => 'media',
		)
	);

	public static $netbook_sheet = array(
		'common' => array(
			'indication' => array(
				'mobilephone',
				'operatingsystem'),
			'market_information',
			'internal_memory',
			'extendable_memory',
			'ram',
			'display' => array(
				'component_id',
				'display_size_diagonally',
				'display_resolution_x',
				'display_resolution_y',
				'color_space_type_id',
				'display_type_id'
			),
			'body' => array(
				'body',
				'color',
				'material'
			),
			'processor',
			'battery',
			'camera',
			'resistance',
			'accessories',
		),
		'technology' => array(
			'input_method',
			'internal_memory',
			'ports' =>  array(
				'data_port',
				'audio_port'
			),
			'wireless' => array(
 				'wifi_networks',
 				'bluetooth_networks',
 				'bluetooth_networks_protocoll'
 			),
			'gsm_runtime',
			'umts_runtime',
			'battery_runtime_audio',
			'battery_runtime_video',
 			'antenna',
 			'additional_hardware'
 		),
		'communication' => array(
			'phone' => array(
				'gsm_networks',
				'cdma_networks',
				'umts_networks',
				'hspa_networks'
			),
			'wireless' => array(
				'wifi_networks',
				'bluetooth_networks',
				'bluetooth_networks_protocoll',
			),
		),
		'multimedia' => array(
			'audio_player' => array(
				'audio_player_functions',
				'audio_player_specials',
				'music_format',
				'playlist_functions',
			),
			'audio_recording' => array(
				'audio_recording_formats',
				'audio_recording_functions'
			),
			'radio_tuner' => array(
				'radio_frequency',
				'radio_tuner'
			),
			'fm_transmitter',
			'camera' => array(
				'camera',
				'camera_chip',
				'finder',
				'focus',
				'zoom'
			),
			'picture_enhancents' => array(
				'brightness_controll',
				'white_balance',
				'image_stabilizer',
				'scene_mode',
				'camera_programs'
			),
			'video_recording' => array(
				'video_camera',
				'video_scene_mode',
				'white_balance',
				'microphone'
			),
			'video_playback' => array(
				'video_player_functions'
			),
			'flash' => array(
				'flash',
				'flash_functions'
			),
			'picture_display' => array(
				'picture_format'
			),
			'picture_editing',
			'ports' => array(
				'data_port',
				'audio_port'
			),
		),
		'Comments' => array(
			'comments' => 'comments'
		),
		'Media' => array(
			'media' => 'media'
		)
	);
	
	public static function get_sheet($device_id = null)
	{
		$main_type = is_null($device_id)
			? null
			: DBManager::Get('devices')->query("SELECT device_type_name FROM device_device_types WHERE device_id = ?", $device_id)->fetch_item();

		switch ($main_type)
		{
			case 'NETBOOK':
				$sheet = self::$netbook_sheet;
				break;
			case 'TABLET':
				$sheet = self::$netbook_sheet;
				break;
			default:
				$sheet = self::$mobilephone_sheet;
				break;
		}
		return $sheet;
	}
}
?>