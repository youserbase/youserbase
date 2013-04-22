<li class="activities">
	<a href="<?=FrontController::GetLink('User', 'Profile', 'Index')?>">
		<span id="poll_activities"><?=Activity::GetNewSince(Youser::Id(), Session::Get('activity', 'timestamp'))?></span>
		<img src="<?=Assets::Image('famfamfam/status_offline.png')?>" alt_phrase="DOCK_ACTIVITIES" title_phrase="DOCK_ACTIVITIES"/>
	</a>
</li>
