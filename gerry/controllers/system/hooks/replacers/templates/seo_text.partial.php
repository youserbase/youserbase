<?php $info = false;?>
<?php if(isset($data)):?>
	<?php if(!in_array(BabelFish::GetLanguage(), array('se', 'no', 'ro', 'dk'))):?>
		<phrase id="THE"/>
	<?php endif;?>
	<device id="<?=$device_id?>"/>
	<?php if(isset($data['indication'][0]['indication_type_id']) && $data['indication'][0]['indication_type_id'] != 'indication_type_id'):?>
		<?php $info = true;?>
		<phrase id="IS_DESCRIBED_AS_AN"/> <phrase id="<?=implode('"/>, <phrase id="', (array)$data['indication'][0]['indication_type_id'])?>"/><?=BabelFish::GetLanguage()=='de'?'.':''?>
		<?php if(isset($data['indication'][1]['operatingsystem_type_id']) && is_array($data['indication'][1]['operatingsystem_type_id'])):?>
			 <phrase id="OS_IS"/> <phrase id="<?=reset($data['indication'][1]['operatingsystem_type_id'])?>"/>.
		<?php else:?>
.
		<?php endif;?>
	<?php endif;?>
	<?php if(!$info):?>
		<phrase id="HAS_NOT_BEEN_INDICATED"/>.
	<?php endif;?>
	<?php if(isset($data['display'])):?>
		<?php if(isset($data['display']['display_size_diagonally']['value'])):?>
			<phrase id="DISPLAY_HAS_SIZE"/> <?=$data['display']['display_size_diagonally']['value']?> <phrase id="<?=$data['display']['display_size_diagonally']['unit']?>"/><?=BabelFish::GetLanguage()=='de'?'.':''?>
		<?php endif;?>
		<?php if(isset($data['display']['display_resolution_x'][0])):?>
			<phrase id="IT_HAS_A_RESOLUTION_OF"/> <?=$data['display']['display_resolution_x'][0]?> * <?=$data['display']['display_resolution_y'][0]?> <phrase id="PIXEL_DOT"/>
		<?php endif;?>
		<?php if(isset($data['display']['color_space_type_id']) && is_array($data['display']['color_space_type_id'])):?>
			<phrase id="DISPLAYS"/> <phrase id="<?=reset($data['display']['color_space_type_id'])?>"/> <phrase id="COLORS"/>.
		<?php else:?>
.
		<?php endif;?>
	<?php endif;?>
	<?php if(isset($data['radiation']['radiation_sar']) && $data['radiation']['radiation_sar'] !== 'radiation_sar'):?>
		<phrase id="IT_HAS_A"/> <phrase id="RADIATION_SAR"/> <phrase id="OF"/> <?=reset($data['radiation']['radiation_sar']);?> W/kg.
	<?php endif;?>

<?php endif;?>