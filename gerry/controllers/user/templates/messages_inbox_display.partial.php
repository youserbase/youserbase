<tr class="r<?=$partial_index?> a<?=$partial_index%2?> <?=$read_timestamp===null?'new':''?>">
	<td>
		<input type="checkbox" name="messages[]" value="<?=$message_id?>"/>
	</td>
	<td>
		<youser id="<?=$sender_id?>" image="small"/>
	</td>
	<td>
		<youser id="<?=$sender_id?>"/>
	</td>
	<td><?=date('d.m.Y H:i', $timestamp)?></td>
	<td>
		<a class="ajax toggle class:message_content effect:blind" href="<?=FrontController::GetLink('Read', array('message_id'=>$message_id))?>">
			<?=BoxBoy::Prepare($subject)?>
		</a>
	</td>
	<td>
		<a class="lightbox" href="<?=FrontController::GetLink('User', 'Messages', 'Send', array('to'=>$sender_id, 'reply_to'=>$message_id))?>">
			<img src="<?=Assets::Image('famfamfam/email_go.png')?>" alt_phrase="REPLYMESSAGE"/>
		</a>
		<a href="<?=FrontController::GetLink('User', 'Messages', 'Delete', array('type'=>'inbound', 'message_id'=>$message_id))?>" class="confirm" title_phrase="DELETEMESSAGE">
			<img src="<?=Assets::Image('famfamfam/email_delete.png')?>" alt_phrase="DELETEMESSAGE"/>
		</a>
	</td>
</tr>