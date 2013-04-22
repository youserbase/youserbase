<?php
	$first = true;
	foreach ($tabs as $name=>$value)
	{
		$first = ($first and (is_array($value) and !FrontController::IsLocation($value)));
	}
	$index = 0;
?>
<?php foreach ($tabs as $name=>$value): ?>
	<a name="<?=is_array($value)?end($value):(is_numeric($name)?$value:$name)?>"></a>
<?php endforeach; ?>
<?php foreach ($tabs as $name=>$value): ?>
	<?php $current_tab = (($first and $index==0) or (is_array($value) and FrontController::IsLocation($value))); ?>
<div id="<?=is_array($value)?end($value):(is_numeric($name)?$value:$name)?>_<?=$index?>" class="tab-container ui-tabs-hide <?=$current_tab?'loaded':''?>" rel="tabs_<?=md5(serialize($tabs))?>">
<?php if ($current_tab): ?>
	<?=call_user_func_array(array('Controller', 'RenderAndDisplay'), is_array($value)?$value:array(is_numeric($name)?$value:$name))?>
<?php endif; ?>
</div>
	<?php $index += 1; ?>
<?php endforeach; ?>
