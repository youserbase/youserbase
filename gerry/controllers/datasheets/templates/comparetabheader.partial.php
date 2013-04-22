<div id="data_sheet" class="sliding_nav clearfix">
	<ul>
		<?php foreach($sheet as $page => $pagedata):?>
			<li <?=$page==$tab?'class="ui-tabs-selected"':''?>>
				<a title="<?=$page?>" href="<?=FrontController::GetLink('datasheets', 'Datasheets_Compare', 'Index', array('tab' => $page, 'compare_id' => $compare_id))?>"><phrase id="<?=strtoupper($page)?>"/>
			<?php if(isset($numbers[$page])):?>
				 (<?=numberformat($numbers[$page])?>)
			<?php endif;?>
				</a>
			</li>
		<?php endforeach;?>
	</ul>
	<div class="clr"></div>
</div>