<tr class="r<?=$partial_index?> a<?=$partial_index%2?>">
	<td>
		<input type="checkbox" name="messages[]" value="<?=$message_id?>"/>
	</td>
	<td>
		<youser id="<?=$receiver_id?>" image="small"/>
	</td>
	<td>
		<youser id="<?=$receiver_id?>"/>
	</td>
	<td><?=date('d.m.Y H:i', $timestamp)?></td>
	<td>
		<a class="ajax toggle class:message_content effect:blind" href="<?=FrontController::GetLink('Read', array('message_id'=>$message_id))?>">
			<?=BoxBoy::Prepare($subject)?>
		</a>
	</td>
	<td>
		<a class="lightbox" href="<?=FrontController::GetLink('User', 'Messages', 'Send', array('to'=>$receiver_id))?>">
			<img src="<?=Assets::Image('famfamfam/email_edit.png')?>" alt_phrase="NEWMESSAGE"/>
		</a>
		<a href="<?=FrontController::GetLink('User', 'Messages', 'Delete', array('type'=>'outbound', 'message_id'=>$message_id))?>" class="confirm" title_phrase="DELETEMESSAGE">
			<img src="<?=Assets::Image('famfamfam/email_delete.png')?>" alt_phrase="DELETEMESSAGE"/>
		</a>
	</td>
</tr>