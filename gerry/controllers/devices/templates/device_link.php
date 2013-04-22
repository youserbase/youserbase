<?php
	$device_name = empty($device['device_names_name'])
		? 'UNKNOWN_DEVICE'
		: $device['device_names_name'];
	$manufacturer_name = empty($device['manufacturer_name'])
		? 'UNKNOWN_MANUFACTURER'
		: $device['manufacturer_name'];

	$phrase = empty($highlight)
		? '<phrase id="%s"/>'
		: '<phrase id="%s" highlight="'.$highlight.'"/>';

	$device_name = sprintf($phrase, $device_name);
	if ($manufacturer):
		$device_name = sprintf($phrase, $manufacturer_name).' '.$device_name;
	endif;

	$quiet_name = str_replace('/>', ' quiet="true"/>', $device_name);
?>
<div class="device tipsify south <?=empty($type)?'':$type?>" title="<?=$quiet_name?>" rel="<?=$device['device_id']?>">
<?php if (!empty($rating)): ?>
	<div class="rating v<?=floor($device['rating'])?>">
		<?=number_format($device['rating'], 1, Locale::Get('decimal_separator'), Locale::Get('thousands_separator'))?>
	</div>
<?php endif; ?>
	<a href="<?=$link?>" class="device-link">
	<?php if (empty($image)): ?>
		<?=$device_name?>
	<?php else: ?>
		<img src="<?=$image?>" alt="<?=$quiet_name?>"/>
	<?php endif; ?>
	</a>
<?php if (!empty($image)): ?>
	<span class="cooliris-icon" rel="<?=FrontController::GetAbsoluteURI()?>Device/<?=$device['device_id']?>/photos.rss" title_phrase="DEVICE_COOLIRIS_IMAGES">&nbsp;</span>
<?php endif; ?>
	<?php if (!empty($append)): ?><?=$append?><?php endif; ?><?php if (!empty($append_tag)): ?><<?=$append_tag?>/><?php endif; ?>
</div>