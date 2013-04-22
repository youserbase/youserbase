<?php if ($total > $limit): ?>
<div class="floatbox">
	<?php if ($skip > 0): ?>
	<div class="fleft">
		<a href="<?=FrontController::GetLink('Plugin', 'DeviceSimilarity', 'skip_device', array('skip' => $skip-$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content">
			<img src="<?=Assets::Image('famfamfam/control_rewind.png')?>" alt="previous"/>
		</a>
	</div>
	<?php endif;?>

	<?php if ($skip+$limit < $total): ?>
	<div class="fright">
		<a href="<?=FrontController::GetLink('Plugin', 'DeviceSimilarity', 'skip_device', array('skip' => $skip+$limit, 'return_to'=>FrontController::GetURL()))?>" class="ajax target:closest:.content">
			<img src="<?=Assets::Image('famfamfam/control_fastforward.png')?>" alt="next"/>
		</a>
	</div>
	<?php endif;?>
</div>
<?php endif; ?>

</div>

<?php // TODO: We need extra spaces ?>
<ul class="floated device_list">
	<?php foreach ($similarity as $id => $device_similarity): ?>
	<li>
		<device id="<?=$id?>" type="avatar"/>
		<?=numberformat($device_similarity*100, 2)?>%
	</li>
	<?php endforeach; ?>
</ul>
