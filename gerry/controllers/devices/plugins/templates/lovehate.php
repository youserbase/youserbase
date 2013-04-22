<?php // TOOO: Muss noch weiter überarbeitet werden bezüglich if/else ?>

<?php if (Youser::Id()): ?>

<?php if (empty($got_this_device)): ?>
<div class="floatbox clearfix">
	<a class="fleft like sprite front" href="<?=FrontController::GetLink('Plugin', 'LoveHate', 'AddDevice', array('device_id'=>$device_id, 'like' => 'love',  'return_to'=>FrontController::GetURL()))?>">
		<phrase id="I_LIKE_IT"/>
	</a>
	<a class="dislike fright sprite front" href="<?=FrontController::GetLink('Plugin', 'LoveHate', 'AddDevice', array('device_id'=>$device_id, 'like' => 'hate',  'return_to'=>FrontController::GetURL()))?>">
		<phrase id="I_DONT_LIKE_IT"/>
	</a>
</div>
<?php endif;?>
<?php if (!empty($lovers)): ?>
<div class="lovers">
	<?=numberformat(0+@$quantities['love'])?> <phrase id="LOVER<?=@$quantities['love']>1?'S':''?>"/><br />
<?php foreach ($lovers as $youser_id): ?>
	<youser id="<?=$youser_id?>" type="avatar" />
	<?php if(Youser::Id() == $youser_id):?>
	<a href="<?=$links['remove']?>">
		<img src="<?=Assets::Image('famfamfam/phone_delete.png')?>" alt_phrase="NOT_MY_DEVICE" />
	</a>
	<?php endif;?>
<?php endforeach; ?>
	<?php if (count($lovers) < @$quantities['love']): ?>&hellip;<?php endif; ?>
</div>
<?php endif;?>
<?php if (!empty($haters)): ?>
<div class="haters">
	<?=numberformat(0+@$quantities['hate'])?> <phrase id="HATER<?=@$quantities['hate']>1?'S':''?>"/><br />
<?php foreach ($haters as $youser_id): ?>
	<youser id="<?=$youser_id?>" type="avatar" />
	<?php if(Youser::Id() == $youser_id):?>
	<a href="<?=$links['remove']?>">
		<img src="<?=Assets::Image('famfamfam/phone_delete.png')?>" alt="NOT_MY_DEVICE"/>
	</a>
	<?php endif;?>
<?php endforeach; ?>
	<?php if (count($haters) < @$quantities['hate']): ?>&hellip;<?php endif; ?>
</div>
<?php endif;?>

<?php return;
/**************************************************************************************/
	endif; ?>

<div class="floatbox clearfix">
	<a class="lightbox fleft like sprite front" href="<?=FrontController::GetLink('User', 'User_Access', 'Login', array('device_id'=>$device_id, 'return_to'=>FrontController::GetURL()))?>">
		<phrase id="I_LIKE_IT"/>
	</a>
	<a class="lightbox fright dislike sprite front" href="<?=FrontController::GetLink('User', 'User_Access', 'Login', array('device_id'=>$device_id, 'return_to'=>FrontController::GetURL()))?>">
		<phrase id="I_DONT_LIKE_IT"/>
	</a>
</div>
<?php if (!empty($lovers)): ?>
<div class="lovers">
	<?=numberformat(0+@$quantities['love'])?> <phrase id="LOVER<?=@$quantities['love']>1?'S':''?>"/><br />
<?php foreach ($lovers as $youser_id): ?>
	<youser id="<?=$youser_id?>" type="avatar" />
<?php endforeach; ?>
	<?php if (count($lovers) < @$quantities['love']): ?>&hellip;<?php endif; ?>
</div>
<?php endif;?>
<?php if (!empty($haters)): ?>
<div class="haters">
	<?=numberformat(0+@$quantities['hate'])?> <phrase id="HATER<?=@$quantities['hate']>1?'S':''?>"/><br />
<?php foreach ($haters as $youser_id): ?>
	<youser id="<?=$youser_id?>" type="avatar" />
<?php endforeach; ?>
	<?php if (count($haters) < @$quantities['hate']): ?>&hellip;<?php endif; ?>
</div>
<?php endif;?>