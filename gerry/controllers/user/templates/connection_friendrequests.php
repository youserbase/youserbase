<h3><phrase id="OPEN_FRIEND_REQUESTS"/></h3>
<ul style="list-style-type: none;" class="zebra">
<?php foreach ($open_requests as $youser_id): ?>
	<li>
		<youser id="<?=$youser_id?>" image="small"/>
		<youser id="<?=$youser_id?>"/>
		(
		<a href="<?=FrontController::GetLink('System', 'System', 'Confirm', array('key'=>$confirmation_data[$youser_id]))?>">
			<phrase id="CONFIRM"/>
		</a>
		/
		<a href="<?=FrontController::GetLink('System', 'System', 'DeclineConfirmation', array('key'=>$confirmation_data[$youser_id], 'redirect_to'=>FrontController::GetLink()))?>">
			<phrase id="DECLINE"/>
		</a>
		)
	</li>
<?php endforeach; ?>
</ul>