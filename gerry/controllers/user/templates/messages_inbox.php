<?php if (count($messages)==0): ?>

<phrase id="NOMESSAGESININBOX"/>

<?php else: ?>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$inbound_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('mail', 'pagination_count')?>" link_class="ajax target:tab"/>
<form action="<?=FrontController::GetLink('Inbox_Actions')?>" method="post">
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
			<td colspan="2"><phrase id="MESSAGESENDER"/></td>
			<td><phrase id="MESSAGEDATE"/></td>
			<td><phrase id="SUBJECT"/></td>
			<td><phrase id="OPTIONS"/></td>
		</tr>
	</thead>
	<tbody>
		<?=$this->render_partials('messages_inbox_display', $messages)?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<input type="submit" name="delete" value_phrase="MESSAGEDELETE" class="confirm" title_phrase="DELETEMESSAGES"/>
				<input type="submit" name="read" value_phrase="MESSAGETOGGLEREAD"/>
				<input type="submit" name="unread" value_phrase="MESSAGETOGGLEUNREAD"/>
			</td>
		</tr>
	</tfoot>
</table>
</form>
<pagination href="<?=FrontController::GetLink(array('page'=>''))?>" total="<?=$inbound_count?>" current_page="<?=$current_page?>" max="<?=Config::Get('mail:pagination_count')?>" link_class="ajax target:tab"/>
<?php endif; ?>