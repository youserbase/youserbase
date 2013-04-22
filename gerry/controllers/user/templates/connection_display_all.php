<?php if ($friend_count==0): ?>
<phrase id="NO_FRIENDS"/>
<?php else: ?>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$friend_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('connection:pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>
<table cellpadding="2" cellspacing="0" class="message_list zebra">
	<colgroup>
		<col width="100px"/>
		<col/>
	</colgroup>
	<tbody>
	<?php foreach ($yousers as $youser): ?>
		<?=$this->render_partial('connection_display', $youser)?>
	<?php endforeach; ?>
	</tbody>
</table>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$friend_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('connection:pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>
<?php endif; ?>