<div id="data_sheet" class="sliding_nav clearfix">
	<ul>
	<?php foreach($sheet as $page => $pagedata):?>
		<li <?=$page==$tab?'class="ui-tabs-selected"':''?>>
		<?php if(isset($device_ids) and count($device_ids)>1):?>
			<a title="<?=$page?>" href="<?=FrontController::GetLink('datasheets', 'Datasheets_Compare', 'Index', array('tab' => $page))?>"><phrase id="<?=strtoupper($page)?>"/>
		<?php else: ?>
			<a title="<?=$page?>" href="<?=FrontController::GetLink('page', array('device_id' => $device_id, 'tab' => $page))?>"><phrase id="<?=strtoupper($page)?>"/>
		<?php endif; ?>
		<?php if(isset($numbers[$page])):?>
			 (<?=numberformat($numbers[$page])?>)
		<?php endif;?>
			</a>
		</li>
	<?php endforeach;?>
	</ul>
</div>