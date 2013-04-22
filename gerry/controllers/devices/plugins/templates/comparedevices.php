<ul class="devices">
<?php foreach ($devices as $id): ?>
	<li>
		<device id="<?=$id?>" type="avatar"/>
		<a href="<?=FrontController::GetLink('Plugin', 'CompareDevices', 'remove_device', array('device_id' => $id, 'return_to'=>FrontController::GetURL()))?>">
			<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="REMOVE_FROM_COMPARE" title_phrase="REMOVE_FROM_COMPARE"/>
		</a>
	<?php // Do we need a rating here???
	/*
		<div class="ratingstar">
			<div style="width:<?=$data['rating'][0]?>%">&nbsp;</div>
		</div>
	*/
	?>
	</li>
<?php endforeach; ?>
</ul>
<div class="compare">
	<a href="<?=FrontController::GetLink('datasheets', 'Datasheets_Compare', 'Index')?>">
		<img src="" alt_phrase="COMPARE"/>
	</a>
</div>
<div class="clr"></div>