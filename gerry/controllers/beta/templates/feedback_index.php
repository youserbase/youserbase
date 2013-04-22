<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" current_page="<?=floor($display_skip/$display_limit)?>" total="<?=$total?>" max="<?=$display_limit?>"/>
<table id="feedback_table" cellpadding="2" cellspacing="0" style="width: 100%;">
	<colgroup>
		<col width="125px"/>
		<col width="125px"/>
		<col/>
		<col width="60px"/>
	</colgroup>
	<thead>
		<tr>
			<th><phrase id="YOUSER"/></th>
			<th><phrase id="TIMESTAMP"/></th>
			<th><phrase id="MESSAGE"/></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php for ($i=0; $i<$display_limit; $i++): ?>
		<tr class="r<?=$i?> a<?=$i%2?>" style="vertical-align: top;">
	<?php if (isset($feedback[$i])): ?>
			<td class="r0"><?=Youser::Get($feedback[$i]['youser_id'])->nickname?></td>
			<td class="r1"><?=dateformat($feedback[$i]['timestamp'])?></td>
			<td class="r2">
				<a href="<?=FrontController::GetLink('Read', array('feedback_id'=>$feedback[$i]['feedback_id']))?>" class="ajax toggle">
					<?=string_wrap(preg_replace('/\s+/', ' ', $feedback[$i]['feedback']),60)?>
				</a>
			</td>
			<td class="r3">
				<a href="<?=FrontController::GetLink('Toggle', array('feedback_id'=>$feedback[$i]['feedback_id'], 'page'=>floor($display_skip/$display_limit)))?>">
				<?php if (empty($feedback[$i]['resolved_by'])): ?>
					<img src="<?=Assets::Image('famfamfam/record_red.png')?>" alt_phrase="UNRESOLVED" title_phrase="UNRESOLVED"/>
				<?php else: ?>
					<img src="<?=Assets::Image('famfamfam/record_green.png')?>" alt_phrase="RESOLVED" title="<phrase id="RESOLVED_BY_AT" quiet="true" nickname="<?=Youser::Get($feedback[$i]['resolved_by'])->nickname?>" timestamp="<?=dateformat($feedback[$i]['resolved_timestamp'], null, true)?>"/>"/>
				<?php endif; ?>
				</a>
				<a href="<?=FrontController::GetLink('Delete', array('feedback_id'=>$feedback[$i]['feedback_id'], 'page'=>floor($display_skip/$display_limit)))?>" class="confirm" title_phrase="DELETE_FEEDBACK">
					<img src="<?=Assets::Image('famfamfam/bin.png')?>" alt_phrase="DELETE_FEEDBACK"/>
				</a>
			</td>
	<?php else: ?>
			<td colspan="4">&nbsp;</td>
	<?php endif; ?>
		</tr>
	<?php endfor; ?>
	</tbody>
</table>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" current_page="<?=floor($display_skip/$display_limit)?>" total="<?=$total?>" max="<?=$display_limit?>"/>
