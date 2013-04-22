<?php
/*
<?php if ($skip > 0): ?>
<div style="float:left">
	<a href="<?=FrontController::GetLink('Plugin', 'DeviceHistory', 'skip_device', array('skip' => $skip-$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content">
		<img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="previous"/>
	</a>
</div>
<?php endif;?>

<?php if ($skip + $limit < $total): ?>
<div style="float:right">
	<a href="<?=FrontController::GetLink('Plugin', 'DeviceHistory', 'skip_device', array('skip' => $skip+$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content">
		<img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next"/>
	</a>
</div>
<?php endif;?>
*/?>

<?=$this->render_partial('devices', array('devices'=>array_flip($device_ids), 'narrow'=>true))?>