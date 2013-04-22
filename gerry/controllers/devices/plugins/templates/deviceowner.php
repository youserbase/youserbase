<?php if (!isset($got_this_device)): ?>
<div style="text-align:center">
	<?php if (Youser::Id()): ?>
	<a class="sprite front mine" href="<?=FrontController::GetLink('Plugin', 'DeviceOwner', 'AddDevice', array('device_id'=>$device_id, 'return_to'=>FrontController::GetURL()))?>">
	<?php else:?>
	<a class="lightbox sprite front mine" href="<?=FrontController::GetLink('User', 'User_Access', 'Login', array('device_id'=>$device_id, 'return_to'=>FrontController::GetURL()))?>">
	<?php endif; ?>
		<phrase id="MY_DEVICE"/>
	</a>
</div>
<?php endif; ?>

<?php if (!empty($yousers)): ?>
<div class="owners">
	<?=numberformat($youser_count)?> <phrase id="USER"/><br />
<?php foreach ($yousers as $youser_id): ?>
	<youser id="<?=$youser_id?>" image="avatar"/>
	<?php if(Youser::Id() == $youser_id):?>
		<a href="<?=FrontController::GetLink('Plugin', 'DeviceOwner', 'RemoveDevice', array('device_id'=>$device_id, 'return_to'=>FrontController::GetURL()))?>">
			<img src="<?=Assets::Image('famfamfam/phone_delete.png')?>" alt="NOT_MY_DEVICE"/>
		</a>
	<?php endif;?>
<?php endforeach; ?>
	<?php if (count($yousers) < $youser_count): ?>&hellip;<?php endif; ?>
</div>
<?php endif;?>