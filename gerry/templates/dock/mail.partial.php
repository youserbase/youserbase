<li class="mail">
	<a href="<?=FrontController::GetLink('User', 'Messages', 'Index')?>">
		<span id="poll_messages"><?=Message::GetUnreadMessages(Youser::Id())?></span>
		<span class="dock-sprite email icon" title_phrase="DOCK_MAILS">&nbsp;</span>
	</a>
</li>