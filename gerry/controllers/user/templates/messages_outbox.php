<?php if (count($messages)==0): ?>
<phrase id="NOMESSAGESINOUTBOX"/>
<?php else: ?>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$outbound_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('mail', 'pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>
<form action="<?=FrontController::GetLink('Outbox_Actions')?>" method="post" class="message_list">
<table cellpadding="2" cellspacing="0" class="message_list zebra">
	<colgroup>
		<col width="20px"/>
		<col width="60px"/>
		<col width="100px"/>
		<col width="100px"/>
		<col/>
		<col width="80px"/>
	</colgroup>
	<thead>
		<tr>
			<td style="text-align: left;">
				<input type="checkbox" name="all_messages" value="<?=implode(',', array_map(create_function('$a', 'return $a["message_id"];'), $messages))?>"/>
			</td>
			<td colspan="2"><phrase id="MESSAGERECIPIENT"/></td>
			<td><phrase id="MESSAGEDATE"/></td>
			<td><phrase id="SUBJECT"/></td>
			<td><phrase id="OPTIONS"/></td>
		</tr>
	</thead>
	<tbody>
		<?=$this->render_partials('messages_outbox_display', $messages)?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<input type="submit" name="delete" value_phrase="MESSAGEDELETE" class="confirm" title_phrase="DELETEMESSAGES"/>
			</td>
		</tr>
	</tfoot>
</table>
</form>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$outbound_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('mail:pagination_count')?>" link_class="ajax target:tab" style="float: right;"/>
<?php endif; ?>