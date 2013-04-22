<?php
	$device_name = empty($device['name'])
		? 'UNKNOWN_DEVICE'
		: $device['name'];
	$manufacturer_name = empty($device['manufacturer'])
		? 'UNKNOWN_MANUFACTURER'
		: $device['manufacturer'];

	$phrase = empty($highlight)
		? '<phrase id="%s"/>'
		: '<phrase id="%s" highlight="'.$highlight.'"/>';

	$device_name = sprintf($phrase, $device_name);
	if ($manufacturer)
		$device_name = sprintf($phrase, $manufacturer_name).' '.$device_name;

	$quiet_name = str_replace('/>', ' quiet="true"/>', $device_name);
?>
<div <?=empty($identifier)?'':'id="'.$identifier.'"'?> class="device <?=empty($image)?'text':($plain?'':'image tipsify south')?> <?=empty($type)?'':$type?>" title="<?=$quiet_name?>" rel="<?=$device['id']?>">
<?php if (!empty($rating)): ?>
	<div class="rating v<?=floor($device['rating'])?>">
		<?=numberformat($device['rating'], 1)?>
	</div>
<?php endif; ?>
	<a href="<?=$link?>" class="device-link <?=!empty($link_class)?$link_class:''?>">
	<?php if (empty($image)): ?>
		<?=$device_name?>
	<?php else: ?>
		<img class="device_image" src="<?=$image?>" alt="<?=$quiet_name?>"/>
	<?php endif; ?>
	</a>
<?php if (!empty($image) and !$plain): ?>
	<ul class="icons">
		<li>
			<a href="<?=FrontController::GetLink('Dock', 'Dropbox', 'Add', array('id'=>$device['id'], 'return_to'=>FrontController::GetURL()))?>" class="ajax target:#dropbox_count" title_phrase="ADD_TO_DROPBOX" rel="nofollow">
				<img src="<?=Assets::Image('famfamfam/attach.png')?>" alt_phrase="ADD_TO_DROPBOX"/>
			</a>
		</li>
	<?php if ($device['picture_count']): ?>
		<li>
			<a href="<?=FrontController::GetLink('Devices', 'ImageGallery', 'Index', array('device_id'=>$device['id']))?>" class="cooliris lightbox" rel="<?=FrontController::GetAbsoluteURI()?>Device/<?=$device['id']?>/photos.rss" title_phrase="LINK_TO_GALLERY">
				<img src="<?=Assets::Image('famfamfam/images.png')?>" alt_phrase="LINK_TO_GALLERY"/>
			</a>
		</li>
	<?php endif; ?>
	</ul>
<?php endif; ?>
	<?php if (!empty($append)): ?><?=$append?><?php endif; ?><?php if (!empty($append_tag)): ?><<?=$append_tag?>/><?php endif; ?>
</div>