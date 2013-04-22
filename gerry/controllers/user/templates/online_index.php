<div class="rbox">
<h3>
	<?=Youser::GetPeopleOnline()?> <phrase id="YOUSERS_ARE_ONLINE"/>
</h3>
<phrase id="LOGGED_IN_YOUSERS"/>
<ul>
	<?php foreach (Youser::GetUsersOnline() as $value):?>
		<li>
			<youser id="<?=$value?>"/>
		</li>
	<?php endforeach;?>
</ul>
</div>