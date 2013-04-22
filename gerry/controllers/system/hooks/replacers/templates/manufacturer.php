<?php if ($image): ?>
<div class="manufacturer <?=$short_name?> <?=$image?$image:''?>">
<?php endif; ?>
<?php if ($link): ?>
	<a href="<?=FrontController::GetLink('Datasheets', 'Catalogue', 'Manufacturer', array('manufacturer_id'=>$id))?>">
<?php endif; ?>
	<?php if ($image): ?>
		<img src="<?=Assets::Img('manufacturers/%s_logo.%s.png', $short_name, $image)?>" alt_phrase="<?=$name?>"/>
	<?php else: ?>
		<phrase id="<?=$name?>"/>
	<?php endif; ?>
<?php if ($link): ?>
	</a>
<?php endif; ?>
<?php if ($image): ?>
</div>
<?php endif; ?>