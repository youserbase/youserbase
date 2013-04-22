<div>
	<div style="float:left">
		<?php if($skip > 0):?>
			<a href="<?=FrontController::GetLink('datasheets', 'Datasheets_Browsing', 'browseBest', array('skip' => $skip+2))?>">
				<img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="next">
			</a>
		<?php endif;?>
	</div>
	<div style="float:right">
		<?php if(count($device_ids) > 8):?>
			<a href="<?=FrontController::GetLink('datasheets', 'Datasheets_Browsing', 'browseBest', array('skip' => $skip-2))?>">
				<img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next">
			</a>
		<?php endif;?>
	</div>
	<div class="clr"></div>
</div>

<div class="devices">
<?php foreach ($device_ids as $device_id): ?>
	<div style="float:left; width:120px;">
		<div class="device_data" style="height:25px; text-align:center;">
			<device id="<?=$device_id?>"/>
		</div>
		<div class="device_picture" style="height:120px; text-align:center;">
			<device id="<?=$device_id?>" type="medium" rating="true"/>
		</div>
	</div>
<?php endforeach; ?>
	<div class="clr"></div>
</div>