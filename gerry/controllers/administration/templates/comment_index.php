<h3><phrase id="COMMENTS_ADMINISTRATION"/></h3>
<table>
<?php foreach ($comments as $comment):?>
<tr>
	<td>
		<div>
			<a class="admin_comment" href="<?=FrontController::GetLink('Delete', array('comments_id' => $comment['comments_id'], 'device_id' => $comment['device_id'], 'compare' => $comment['type'], 'return_to' => FrontController::GetAbsoluteURI()))?>">
				<img src="<?=Assets::Image('famfamfam/delete.png')?>" alt_phrase="DELETE"/>
				
			</a>
		</div>
		<div>
			<a class="admin_comment" href="<?=FrontController::GetLink('Publish', array('comments_id' => $comment['comments_id'], 'device_id' => $comment['device_id'], 'compare' => $comment['type'], 'return_to' => FrontController::GetAbsoluteURI()))?>">
				<img src="<?=Assets::Image('famfamfam/add.png')?>" alt_phrase="PUBLISH"/>
				
			</a>
		</div>
	</td>
	<th>
		<strong><device id="<?=$comment['device_id']?>" quiet="true"/> | <?=$comment['guest_name']?> </br
		<?=$comment['website']?></strong>
	</th>
</tr>
<tr>
	<td></td>
	<td><?=$comment['comment']?></td>
</tr>
<?php endforeach;?>
</table>