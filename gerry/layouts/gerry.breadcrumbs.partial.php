<a href="<?=FrontController::GetLink()?>">
	<?=$page_title?>
</a>

<?php return; ?>

<?php $first = true; ?>
<?php foreach (FrontController::GetBreadCrumbs() as $index=>$data): ?>
	<?php if (!$first): ?>
	-
	<?php else: $first=false; endif; ?>
	<a href="<?=call_user_func_array(array('FrontController', 'GetLink'), $data)?>">
		<phrase id="PAGETITLE_<?=$index?>"/>
	</a>
<?php endforeach; ?>
