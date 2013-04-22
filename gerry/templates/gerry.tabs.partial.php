<?php
	$first = true;
	foreach ($tabs as $name=>$value)
	{
		$first = ($first and (is_array($value) and !FrontController::IsLocation($value)));
	}
	$index = 0;
?>
<div class="sliding_nav">
	<?php if ('TABS_RIGHT' == (!empty($POSITION)?$POSITION:null)): ?>
		<div style="float: right;"><?=$CONTENT?></div>
	<?php endif; ?>
	<ul class="tabify" id="tabs_<?=md5(serialize($tabs))?>">
	<?php foreach ($tabs as $name=>$value): ?>
		<?php $current_tab = (($first and $index==0) or (is_array($value) and FrontController::IsLocation($value))); ?>
		<li <?=$current_tab?'class="ui-tabs-selected"':''?>>
			<a href="<?=call_user_func_array(array('FrontController', 'GetLink'), is_array($value)?$value:array(is_numeric($name)?$value:$name))?>" title="<?=is_array($value)?end($value):(is_numeric($name)?$value:$name)?>_<?=$index?>">
				<?=is_array($value)?'<phrase id="PAGETITLE_'.$name.'"/>':(preg_match('/[^[:alnum:]_]/', is_array($value)?'foo':$value)?$value:'<phrase id="TAB_'.strtoupper($value).'"/>')?>
			</a>
		</li>
		<?php $index+=1; ?>
	<?php endforeach; ?>
	</ul>

	<div class="clr"></div>
</div>
