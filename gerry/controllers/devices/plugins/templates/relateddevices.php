<?php $total = count($devices); $limit = 6; ?>
<?php if ($total > $limit): ?>
<div class="floatbox">
	<?php if($skip > 0):?>
	<div class="fleft">
		<a href="<?=FrontController::GetLink('Plugin', 'RelatedDevices', 'skip_device', array('skip' => $skip-2, 'return_to'=>FrontController::GetURL()))?>">
			<img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="previous"/>
		</a>
	</div>
	<?php endif;?>
	<?php if ($total > $limit): ?>
	<div class="fright">
		<a href="<?=FrontController::GetLink('Plugin', 'RelatedDevices', 'skip_device', array('skip' => $skip+2, 'return_to'=>FrontController::GetURL()))?>">
			<img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next"/>
		</a>
	</div>
	<?php endif;?>
</div>
<?php endif; ?>

<?=$this->render_partial('devices', array('devices'=>array_flip($devices), 'skip' => $skip))?>
