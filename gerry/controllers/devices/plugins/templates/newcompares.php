<?php if ($total > $limit): ?>
<div class="floatbox">
    <?php if ($skip > 0): ?>
    <div class="fleft">
        <a href="<?=FrontController::GetLink('Plugin', 'NewCompares', 'skip_compare', array('skip' => $skip-$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content"><img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="previous"/></a>
    </div>
    <?php endif; ?>
    <?php if ($skip + $limit < $total): ?>
    <div class="fright">
        <a href="<?=FrontController::GetLink('Plugin', 'NewCompares', 'skip_compare', array('skip' => $skip+$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content"><img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next"/></a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php $count = 0?>
<ul class="zebra">
<?php foreach ($compares as $compare_id => $device_ids):?>
<li class="r<?=$count%2?> clr">
<?php $count++?>
	<a href="<?=FrontController::GetLink('datasheets', 'Compare', 'Index', array('compare_id' => $compare_id))?>"><phrase id="COMPARISON"/> <phrase id="BETWEEN"/></a>
	<?=$this->render_partial('devices', array('devices'=>array_flip($device_ids), 'skip'=>$skip, 'narrow'=>true))?>
</li>
<?php endforeach;?>
</ul>