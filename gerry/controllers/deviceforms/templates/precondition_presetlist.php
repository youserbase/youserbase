<div class="rbox">
	<h3>
		<phrase id="<?=strtoupper($table)?>"/>
		<a class="lightbox" href="<?=FrontController::GetLink('addpreset', array('table' => $table, 'detail' => '*', 'add' => true, 'device_id' => isset($device_id)?$device_id:null, 'tab' => isset($tab)?$tab:null))?>">
			<img src="<?=Assets::Image('famfamfam/add.png')?>" alt_phrase="NEW_PRESET"/>
		</a>
	</h3>
	<ul>
		<?php if(is_array($presets) && !empty($presets)):?>
			<?php foreach ($presets as $key => $value):?>
				<li>
					<a class="lightbox" href="<?=FrontController::GetLink('presetdetails', array('table' => $table, 'detail' => $value, 'device_id' => isset($device_id)?$device_id:null, 'tab' => isset($tab)?$tab:null))?>">
						<phrase id="<?=strtoupper($value)?>"/>
					</a>
				</li>
			<?php endforeach;?>
		<?php endif;?>
	</ul>
</div>