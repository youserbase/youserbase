<?php
class Datasheets_Admin extends Controller
{
	public function Index()
	{
		$skip_device = 0;
		if(isset($_REQUEST['skip_device']))
		{
			$skip_device = $_REQUEST['skip_device']+10;
		}
		$skip_components = 0;
		if(isset($_REQUEST['skip_components']))
		{
			$skip_components = $_REQUEST['skip_components']+10;
		}
		$template = $this->get_template(true);
		$device_count = self::unconfirmed_devices(true);
		$devices = self::unconfirmed_devices(false, $skip_device);
		$components_count = self::unconfirmed_components(true);
		$devices_by_components = self::unconfirmed_components(false, $skip_components);
		$components = array();
		if(is_array($devices_by_components))
		{
			foreach ($devices_by_components as $device_id => $table_names)
			{
				foreach ($table_names as $table_name => $component_id)
				{
					$components[$device_id][$table_name] = self::get_components($table_name, $component_id);
				}
			}
		}
		$template->assign('skip_device', $skip_device);
		$template->assign('skip_components', $skip_components);
		$template->assign('device_count', $device_count);
		$template->assign('components_count', $components_count);
		$template->assign('device_ids', $devices);
		$template->assign('device_components_ids', $components);
	}
	
	public function confirm()
	{
		if(!isset($_POST['table_name']))
		{
			FrontController::Relocate('Index');
		}
		$table_name = $_POST['table_name'];
		if(!isset($_REQUEST['device_id']))
		{
			FrontController::Relocate('Index');
		}
		$device_id = $_REQUEST['device_id'];
		if(is_array($table_name))
		{
			foreach ($table_name as $table)
			{
				$explode = explode(',', $table);
				$t_name = $explode[0];
				$component_id = $explode[1];
				DBManager::Get('devices')->query("UPDATE device_components SET confirmed = 'yes' WHERE device_id = ? AND table_name = ? AND component_id = ?;", $device_id, $t_name, $component_id);
				$youser_id = DBManager::Get('devices')->query("SELECT youser_id FROM device_components WHERE component_id = ?;", $component_id)->fetch_item();
				YouserCredits::Reward($youser_id, 5, 'scruffy', 'edit');
			}
		}
		else
		{
			$explode = explode(',', $table_name);
			$t_name = $explode[0];
			$component_id = $explode[1];
			DBManager::Get('devices')->query("UPDATE device_components SET confirmed = 'yes' WHERE device_id = ? AND table_name = ? AND component_id = ?;", $device_id, $t_name, $component_id);
			$youser_id = DBManager::Get('devices')->query("SELECT youser_id FROM device_components WHERE component_id = ?;", $component_id)->fetch_item();
			YouserCredits::Reward($youser_id, 5, 'scruffy', 'edit');
		}
		FrontController::Relocate('Index');
	}
	
	public function update_device()
	{
		if(isset($_REQUEST['confirm_id']))
		{
			self::confirm_device($_REQUEST['confirm_id']);
		}
		else if(isset($_REQUEST['delete_id']))
		{
			self::delete_device($_REQUEST['delete_id']);
		}
	}
	
	public function confirm_device($device_id)
	{
		if(is_array($device_id))
		{
			foreach ($device_id as $id)
			{
				DBManager::Get('devices')->query("UPDATE device SET confirmed = 'yes' WHERE device_id = ?;", $id);
				$youser_id = DBManager::Get('devices')->query("SELECT youser_id FROM device WHERE device_id = ?;", $id)->fetch_item();
				YouserCredits::Reward($youser_id, 10, 'scruffy', 'created');
			}
		}
		else
		{
			DBManager::Get('devices')->query("UPDATE device SET confirmed = 'yes' WHERE device_id = ?;", $device_id);
			$youser_id = DBManager::Get('devices')->query("SELECT youser_id FROM device WHERE device_id = ?;", $device_id)->fetch_item();
			YouserCredits::Reward($youser_id, 10, 'scruffy', 'created');
		}
		FrontController::Relocate('Index');
	}
	
	public function delete_device($device_id)
	{
		if(is_array($device_id))
		{
			foreach ($device_id as $id)
			{
				DBManager::Get('devices')->query("INSERT INTO deleted_devices SELECT * FROM device  WHERE device.device_id = ?;", $id);
				DBManager::Get('devices')->query("INSERT INTO deleted_device_names SELECT * FROM device_names  WHERE device_names.device_id = ?;", $id);
				DBManager::Get('devices')->query("DELETE FROM device WHERE device_id = ?;", $id);
				DBManager::Get('devices')->query("DELETE FROM device_names WHERE device_id = ?;", $id);
			}
		}
		else
		{
			DBManager::Get('devices')->query("INSERT INTO deleted_devices SELECT * FROM device  WHERE device.device_id = ?;", $device_id);
			DBManager::Get('devices')->query("INSERT INTO deleted_device_names SELECT * FROM device_names  WHERE device_names.device_id = ?;", $device_id);
				DBManager::Get('devices')->query("DELETE FROM device WHERE device_id = ?;", $device_id);
				DBManager::Get('devices')->query("DELETE FROM device_names WHERE device_id = ?;", $device_id);
		}
		FrontController::Relocate('Index');
	}
	
	public function unconfirmed_devices($count = false, $skip = 0)
	{
		$device_type = null;
		if(isset($_GET['device_type']))
		{
			$device_type = $_GET['device_type'];
		}
		if(!$count)
		{
			$unconfirmed = investigator::get_unconfirmed_devices($device_type, $skip);
		}
		else
		{
			$unconfirmed = DBManager::Get('devices')->query("SELECT COUNT(device_id) FROM device WHERE confirmed != 'yes';")->fetch_item();
		}
		return $unconfirmed;
	}
	
	public function unconfirmed_components($count = false, $skip = 0, $limit = 10)
	{
		$unconfirmed = false;
		if($count == true)
		{
			$device_ids = DBManager::Get('devices')->skip($skip)->query("SELECT DISTINCT(device_id) FROM device_components WHERE confirmed != 'yes' ORDER BY timestamp DESC")->to_array('device_id');
			return count($device_ids);
		}
		$device_ids = DBManager::Get('devices')->skip($skip)->limit($limit)->query("SELECT DISTINCT(device_id) FROM device_components WHERE confirmed != 'yes' ORDER BY timestamp DESC")->to_array('device_id');
		foreach ($device_ids as $device_id => $data)
		{
			$unconfirmed[$device_id] = DBManager::Get('devices')->query("SELECT table_name, component_id FROM device_components WHERE device_id = ? AND confirmed != 'yes' ORDER BY timestamp DESC", $device_id)->to_array('table_name', 'component_id');
		}
		return $unconfirmed;
	}
	
	public function get_components($table_name, $component_id)
	{
		if(class_exists($table_name))
		{
			$object = call_user_func_array(array($table_name, 'Get'), array());
			$component_data = $object->Loadedit($component_id);
			return $component_data;
		}
		return false;
	}
	
	public static function optimize_tables()
	{
		DBManager::Get('devices')->query("OPTIMIZE TABLE _component_mapping,_device_mapping,accessories,accessories_type,additional_hardware,additional_hardware_type,alarm_functions,alarm_functions_type,antenna,antenna_type,audio_player_functions,audio_player_functions_type,audio_player_specials,audio_player_specials_type,audio_port,audio_port_allocation,audio_port_allocation_type,audio_port_type,audio_recording_formats,audio_recording_functions,audio_recording_functions_type,background_picture,background_picture_type,battery,battery_display_runtime,battery_display_runtime_type,battery_pictures_camera,battery_runtime_audio,battery_runtime_phone,battery_runtime_video,battery_size,battery_talktime,battery_type,bluetooth_networks,bluetooth_networks_protocoll,bluetooth_networks_protocoll_type,bluetooth_networks_type,body,body_specials,body_specials_type,body_type,brightness_controll,brightness_controll_type,browser,browser_type,build_in,businesscard,businesscard_type,cable,calculator,calculator_functions,calculator_functions_type,calculator_type,calendars,calendars_functions,calendars_functions_type,call_functions_type,call_notification,call_notification_type,caller_protocoll,caller_protocoll_type,camera,camera_chip,camera_chip_type,camera_macro,camera_programs,camera_programs_type,camera_resolutions_type,cdma_networks,cdma_networks_type,charger,clock,color,color_space_type,color_type,comments,component_similarity,config_sheet,config_tab,config_table,contacts,contacts_functions,contacts_functions_type,contacts_name_sorting_type,continent,country,countrylanguages,currency_type,data_network,data_network_protocolls,data_network_protocolls_type,data_network_type,data_port,data_port_type,device,device_components,device_device_types,device_names,device_pictures,device_rating,device_rating_unique,device_similarity,device_statistics,device_statistics_tmp,device_type,device_type_classes,disclaimer,display,display_average,display_function,display_function_type,display_type,dockingstation,document_edit,document_format_type,document_read,email,email_functions,email_functions_type,email_protocoll,email_protocoll_type,exposure_modes,exposure_modes_type,extendable_memory,extendable_memory_type,feature_rating,finder,finder_type,flash,flash_functions,flash_functions_type,flash_type,fm_transmitter,focus,focus_type,frequency_unit_type,gps,gps_chip,gps_chip_type,gps_function,gps_function_type,gps_software,gps_software_type,gps_type,gsm_networks,gsm_networks_type,gsm_runtime,headphones,holder,hspa_networks,hspa_networks_type,image_stabilizer,image_stabilizer_type,indication_type,input,input_method,input_method_type,input_special_key,input_special_key_type,instant_messenger,instant_messenger_type,internal_memory,internal_memory_type,invitations,keyboard_backlight,keyboard_backlight_type,language,lens,lens_type,mail_limiter,mail_texts,manufacturer,manufacturer_device_types,market_information,market_information_type,material,material_type,memory_size_type,message_function_type,microphone,microphone_type,mms,mms_data,mms_data_type,mobilephone,mobilephone_profiles,mobilephone_profiles_type,modem_wired_connections,modem_wireless_connections,music_format,music_format_type,nav_compass,nav_compass_type,nav_display_output,nav_display_output_type,nav_routing,nav_routing_avoid,nav_routing_avoid_type,nav_routing_type,nav_usage_for_type,nav_voice_output,nav_voice_output_type,network_connection_display,network_connection_display_type,notes,notes_functions,notes_functions_type,offensive_comments,operatingsystem,operatingsystem_type,phone,phone_function,phone_function_type,phone_modem,phone_modem_type,phone_network,phone_network_type,picture_editing,picture_editing_type,picture_format,picture_format_type,picture_playback_type,picture_view_functions,picture_view_functions_type,playlist_functions,playlist_functions_type,power_output_type,predecessors,processor,processor_type,productioncountry,profiles,profiles_type,projection,projection_type,radiation,radio_frequency,radio_frequency_type,radio_tuner,ram,release_quarter_type,resistance,resistance_type,resolution_type,resolution_units_type,ringtone_format,ringtone_format_type,scene_mode,scene_mode_type,screensaver,screensaver_type,secondary_camera,secondary_display,security_features,security_features_type,sendvia_type,shutter,shutter_type,siblings,size_units_type,sms,sound_output,sound_output_type,speaker,speaker_type,sync,sync_software,sync_software_type,sync_type,sync_via,sync_via_type,tab_rating,table_rating,themes,themes_type,time_functions,time_functions_type,time_units_type,tv_transmitter_standards,tv_transmitter_standards_type,tv_tuner,tv_tuner_type,umts_networks,umts_networks_type,umts_runtime,video_camera,video_format_type,video_play_format,video_player,video_player_functions,video_player_functions_type,video_save_format,voice_codec,voice_codec_type,weight_units_type,white_balance,white_balance_type,who_did_id,wifi_networks,wifi_networks_type,zoom,zoom_type;");
		FrontController::Relocate('Index');
	}
	
	public static function transfer_os()
	{
		$os_component = DBManager::Get('devices')->query("SELECT component_id, component_id_int, operatingsystem_type_id FROM mobilephone WHERE operatingsystem_type_id != '' GROUP BY component_id ORDER BY timestamp DESC;")->to_array('component_id');
		$device_component = DBManager::Get('devices')->query("SELECT device_id, device_id_int, m.component_id_int, m.component_id FROM mobilephone AS m LEFT JOIN device_components AS d ON m.component_id = d.component_id WHERE operatingsystem_type_id != '' AND device_id != '' GROUP BY device_id;")->to_array('component_id');
		foreach ($os_component as $component_id => $line)
		{
			if(isset($device_component[$component_id]))
			{
				$os = new operatingsystem(md5(uniqid(time(true))));
				foreach ($line as $key => $value)
				{
					$os->$key = $value;
				}
				$os->timestamp = 'NOW()';
				$os->youser_id = Youser::Id();
				$os->save();
				$dc = new device_components(md5(uniqid(time(true))));
				foreach ($device_component[$component_id] as $key => $value)
				{
					$dc->$key = $value;
				}
				$dc->table_name = 'operatingsystem';
				$dc->timestamp = 'NOW()';
				$dc->confirmed = 'yes';
				$dc->youser_id = Youser::Id();
				$dc->save();
			}
		}
		FrontController::Relocate('Index');
	}
	
	public function unconfirmed_devices_by_components()
	{
		$unconfirmed = investigator::get_unconfirmed_devices_by_components();
		return $unconfirmed;
	}
	
	public function copy_device_names()
	{
		$device_names = DBManager::Get('devices')->query("SELECT device_id, device_names_name FROM device_names;")->to_array('device_id', 'device_names_name');
		foreach ($device_names as $device_id => $device_name)
		{
			DBManager::Get('devices')->query("UPDATE device SET device_name = ? WHERE device_id = ?", $device_name, $device_id);
		}
		FrontController::Relocate('Index');
	}
}