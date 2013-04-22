<div class="rbox">
<?php if($youser->visible):?>
<div id="nick_left">
	<img class="youser" src="<?=Youser_Image::GetLink($youser->id, 'large')?>" alt=""/>
	<br/>
	<ul class="content_nav">
	<?php if (Youser::Get() and Youser::Get()->id != $youser->id): ?>
<?php /** COMMUNITY RAUS
		<li>
			<a href="<?=FrontController::GetLink('Connection', 'Display', array('youser_id'=>$youser->id))?>"><img src="<?=Assets::Image('famfamfam/group.png')?>" alt=""/>
				<phrase id="DISPLAY_ALL_FRIENDS" nickname="<?=$youser->nickname?>">
					Alle Freunde von
					<?=$youser->nickname?>
				</phrase>
			</a>
		</li>
**/ ?>
		<li>
			<a class="lightbox" href="<?=FrontController::GetLink('Messages', 'Send', array('to'=>$youser->id))?>"><img src="<?=Assets::Image('famfamfam/email_edit.png')?>" alt=""/>
				<phrase id="SENDMESSAGETO" to="<?=$youser->nickname?>">
					<?=$youser->nickname?>
					eine Nachricht senden
				</phrase>
			</a>
		</li>
<?php /** COMMUNITY RAUS
		<?php if (!Connection::Connected($youser->id, Youser::Get()->id)): ?>
		<li>
			<a class="lightbox" href="<?=FrontController::GetLink('Connection', 'Add', array('youser_id'=>$youser->id))?>"><img src="<?=Assets::Image('famfamfam/user_add.png')?>" alt=""/>
				<phrase id="ADD_AS_FRIEND" nickname="<?=$youser->nickname?>">
					<?=$youser->nickname?>
					als Freund hinzufügen
				</phrase>
			</a>
		</li>
		<?php else: ?>
		<li>
			<a class="lightbox" href="<?=FrontController::GetLink('Connection', 'Remove', array('youser_id'=>$youser->id))?>"><img src="<?=Assets::Image('famfamfam/user_delete.png')?>" alt=""/>
				<phrase id="REMOVE_AS_FRIEND" nickname="<?=$youser->nickname?>">
					Die Freundschaft mit
					<?=$youser->nickname?>
					beenden
				</phrase>
			</a>
		</li>
		<?php endif; ?>
**/ ?>
	<?php endif; ?>
	</ul>
</div>
<div id="nick_right">
<?php /** COMMUNITY RAUS
	<?php if (Youser::Get() !== false and Youser::Get()->id != $youser->id): ?>
	<div class="block">
		<h2>
		<phrase id="CONNECTION"/></h3>
		<div class="connection">
		<?php if (is_array($connection)): ?>
			<?php $youser_id = array_shift($connection); ?>
				<youser id="<?=$youser_id?>" type="avatar"/>
			<?php foreach ($connection as $youser_id): ?>
				<img src="<?=Assets::Image('famfamfam/arrow_right.png')?>" alt=""/>
				<youser id="<?=$youser_id?>" type="avatar"/>
			<?php endforeach; ?>
		<?php elseif ($connection !== null): ?>
			keine verbindung
		<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
**/ ?>
	<table cellpadding="2" cellspacing="0" id="nickpage_profile">
	<?php $row_count = 0; ?>
	<?php foreach ($profile as $scope => $data): ?>
		<?php if (empty($data)) continue; ?>
		<tbody>
			<tr>
				<td colspan="2" class="rhead">
					<phrase id="PROFILE_<?=strtoupper($scope)?>"/>
				</td>
			</tr>
		<?php foreach ($data as $index => $value): ?>
			<tr class="a<?=$row_count%2?> r<?=$row_count++?>">
				<td class="a0 r0"><phrase id="<?=strtoupper($index)?>"/>:</td>
				<td class="a1 r1">
				<?php if ($value['type']=='date'): ?>
					<?=dateformat($value['value'], 'date')?>
				<?php elseif ($value['type']=='phrase'): ?>
					<phrase id="<?=strtoupper($value['value'])?>"/>
				<?php else: ?>
					<?=BoxBoy::Prepare($value['value'])?>
				<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	<?php endforeach; ?>
	</table>
	<div id="pinboard" rel="<?=FrontController::GetLink('User', 'Nickpage', 'Pinboard', array('youser_id'=>$youser->id))?>">
		<?=Controller::Render('user', 'User_Nickpage', 'Pinboard', $youser->id)?>
	</div>
</div>
<?php else:?>
	<div id="nick_left">
		<img class="youser" src="<?=Youser_Image::GetLink($youser->id, 'large')?>" alt=""/>
	</div>
	<div id="nick_right">
		<?=$youser->nickname?> <phrase id="NICKPAGE_PRIVATE"/>
	</div>
<?php endif;?>
</div>