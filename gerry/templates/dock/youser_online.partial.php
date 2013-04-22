<li>
	<a href="<?=FrontController::GetLink('User', 'Online', 'Index')?>" class="lightbox" title_phrase="YOUSERS_ARE_ONLINE">
		<span id="poll_youseronline"><?=numberformat(Youser::GetPeopleOnline())?></span> <phrase id="YOUSERS_ARE_ONLINE"/>
	</a>
</li>