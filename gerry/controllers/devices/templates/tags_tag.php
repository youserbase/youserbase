<div class="rbox">
	<h2><phrase id="DEVICES_WITH_TAG_X" tag="<?=$tag?>"/></h2>
	<ul>
	<?php $i=0; foreach ($devices as $device_id => $quantity): ?>
		<li class="a<?=$i%2?> r<?=$i++?>">
			<device id="<?=$device_id?>" type="small"/> <device id="<?=$device_id?>"/> (<?=numberformat($quantity)?>)
		</li>
	<?php endforeach; ?>
	</ul>
</div>