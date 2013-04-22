<div>
	<div style="float:left">
		<?php if($skip > 0):?>
			<a href="<?=FrontController::GetLink('datasheets', 'Datasheets_Browsing', 'browseSimilar', array('device_id'=>$device_id, 'skip' => $skip+2))?>">
				<img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="next">
			</a>
		<?php endif;?>
	</div>
	<div style="float:right">
		<?php if(count($similarity) > 8):?>
			<a href="<?=FrontController::GetLink('datasheets', 'Datasheets_Browsing', 'browseSimilar', array('device_id'=>$device_id, 'skip' => $skip-2))?>">
				<img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next">
			</a>
		<?php endif;?>
	</div>
	<div class="clr"></div>
</div>

<div class="devices">
<?php foreach ($similarity as $id => $device_similarity): ?>
	<div style="float:left; width:120px;">
		<div class="device_data" style="height:25px; text-align:center;">
			<device id="<?=$id?>"/>
		</div>
		<div class="device_picture" style="height:120px; text-align:center;">
			<device id="<?=$id?>" type="medium"/>
		</div>
		<div class="device_similarity" style="height:25px; text-align:center;">
			<?=numberformat(round($device_similarity*100, 2))?>%
		</div>
	</div>
<?php endforeach; ?>
	<div class="clr"></div>
</div>
