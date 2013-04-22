<table cellpadding="2" cellspacing="0" style="width: 100%;">
	<thead>
		<tr>
			<th><phrase id="FEED_NAME"/></th>
			<th><phrase id="FEED_LANGUAGE"/></th>
			<th><phrase id="FEED_LASTUPDATE"/></th>
			<th><phrase id="FEED_FAILURE"/></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($feeds as $i=>$feed): ?>
		<tr class="r<?=$i?> a<?=$i%2?>">
			<td>
				<a href="<?=$feed['link']?>" title="<?=$feed['description']?>">
					<img src="<?=$feed['image']?>" alt=""/>
					<?=$feed['title']?>
				</a>
			</td>
			<td><?=$feed['language']?></td>
			<td>
			<?php if (empty($feed['last_update'])): ?>
				<phrase id="NEVER"/>
			<?php else: ?>
				<?=date('d.m.Y H:i', $feed['last_update'])?>
			<?php endif; ?>
			</td>
			<td><?=$feed['failures']?></td>
			<td>
				<a href="<?=FrontController::GetLink('Toggle', array('feed_id'=>$feed['feed_id']))?>">
				<?php if ($feed['status']=='enabled'): ?>
					<img src="<?=Assets::Image('famfamfam/accept.png')?>" alt_phrase="ACTIVE"/>
				<?php else: ?>
					<img src="<?=Assets::Image('famfamfam/stop.png')?>" alt_phrase="INACTIVE"/>
				<?php endif; ?>
				</a>
				<a href="<?=FrontController::GetLink('Delete', array('feed_id'=>$feed['feed_id']))?>" class="confirm" title_phrase="REMOVE_FEED">
					<img src="<?=Assets::Image('famfamfam/bin.png')?>" alt_phrase="DELETE"/>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>