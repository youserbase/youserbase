<div class="rbox comment">
	<h3>
		<img src="<?=Assets::Image('flags/'.$comment['language'].'.png')?>" alt_phrase="<?=$comment['language']?>" title_phrase="<?=$comment['language']?>"/>
		<?=dateformat($comment['timestamp'])?> <b><youser id="<?=$comment['youser_id']?>"/></b> <phrase id="<?=$comment['category']?>"/> 

		<a class="lightbox" href="<?=FrontController::GetLink('Edit', array('device_id' => $comment['device_id'], 'comments_id' => $comment['comments_id'], 'category' => $comment['category']))?>"><phrase id="EDIT_COMMENT"/></a>

		<a class="burn" href="<?=FrontController::GetLink('Undelete', array('device_id' => $comment['device_id'], 'comments_id' => $comment['comments_id'], 'return_to' => str_replace('/COMMENTS', '#COMMENTS', FrontController::GetURL())))?>"><phrase id="UNDELETE"/></a>
	</h3>
	<?=$comment['comment']?>
</div>